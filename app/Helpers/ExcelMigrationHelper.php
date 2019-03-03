<?php
/**
 * Class ExcelMigrationHelper.
 *
 * @author: Ekojoka Christolight Idakwo <chris.idakwo@gmail.com>
 * @date: 30/12/2017
 * @time: 09:10
 * @package: App\Helpers;
 */

namespace App\Helpers;


use App\ActionHistory;
use App\Batch;
use App\Brand;
use App\BrandDealer;
use App\Category;
use App\Dealer;
use App\MeasurementUnit;
use App\Product;
use App\ProductVariation;
use App\SubCategory;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Exception\NotWritableException;
use Intervention\Image\ImageManager;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Ramsey\Uuid\Uuid;

class ExcelMigrationHelper {

    /**
     * @param $table
     * @param $filename
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \Exception
     */
    public static function migrate($table, $filename) {
        $result = [];

        // Load spreadsheet data
        $spreadsheet = IOFactory::load($filename);
        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();
        // Convert each row with data to an array. Return the array count.
        $length = count($sheet->toArray());
        $count = 2;
        $empty_first = 0;
        $row_iterator = $sheet->getRowIterator(2, $length);

        foreach ($row_iterator as $row) {
            $name = $row->getWorksheet()->getCell("A{$count}")->getValue();
            $slug = str_slug($name);

            if($name == "" & $count > 2) {
                $empty_first++;
                if($empty_first == 3) {
                    $result["success"] = "Items have been saved. You have some empty rows.";
                    return $result;
                }

                continue;
            } elseif($name == "" & $count == 2) {
                $result["error"] = "Cannot continue. A{$count} is empty!";
                return $result;
            }

            switch ($table) {
                case "brands":
                    $contact_info = $row->getWorksheet()->getCell("B{$count}")->getValue() ?: "";
                    $address = $row->getWorksheet()->getCell("C{$count}")->getValue() ?: "";

                    Brand::create([
                        "name" => $name,
                        "slug" => $slug,
                        "contact_info" => $contact_info,
                        "address" => $address
                    ]);

                    // Record action history
                    $description = "Added new brand from Excel document migration";
                    ActionHistory::AddNewActionHistory($description);
                    break;

                case "categories":
                    Category::create([
                        "name" => $name,
                        "slug" => $slug
                    ]);

                    // Record action history
                    $description = "Added new category from Excel document migration";
                    ActionHistory::AddNewActionHistory($description);

                    break;

                case "sub_categories":
                    $category = $row->getWorksheet()->getCell("B{$count}")->getValue();

                    SubCategory::create([
                        "name" => $name,
                        "slug" => $slug,
                        "category_id" => Category::where("name", $category)->first()->id
                    ]);

                    // Record action history
                    $description = "Added new sub-category from Excel document migration";
                    ActionHistory::AddNewActionHistory($description);
                    break;

                case "dealers":
                    $brands = $row->getWorksheet()->getCell("B{$count}")->getValue();
                    $phone = $row->getWorksheet()->getCell("C{$count}")->getValue();

                    (new Dealer())->AddNewDealer([
                        "full_name" => $name,
                        "phone" => $phone,
                        "brands" => explode(", ", $brands),
                    ]);

                    // Record action history
                    $description = "Added new dealer from Excel document migration";
                    ActionHistory::AddNewActionHistory($description);
                    break;

                case "measurement_units":
                    MeasurementUnit::create(["name" => $name]);

                    // Record action history
                    $description = "Added new measurement unit from Excel document migration";
                    ActionHistory::AddNewActionHistory($description);
                    break;

                case "products":
                    // Check to ensure there is an image path
                    $image_path = $row->getWorksheet()->getCell("B{$count}")->getValue();
                    if($image_path == "" || $image_path == null) {
                        $result["error"] = "All products must have an image path!";
                        return $result;
                    }

                    $prefix = str_replace(" ", "-", $name);
                    $image_ext = \File::extension($image_path);
                    $image_name = "{$prefix}.$image_ext";
                    $thumb_image_name = "{$prefix}-thumb.$image_ext";
                    $saveDirectory = public_path("inventory\\products\\" . $name);

                    $manager = new ImageManager(array("driver" => "gd"));
                    $image = $manager->make($image_path)->fit(700, 700);
                    $thumb_image = $manager->make($image_path)->fit(250, 250);

                    try{
                        if(\File::exists($saveDirectory)) {
                            \File::chmod($saveDirectory, 0775);
                        } else {
                            \File::makeDirectory($saveDirectory, 0775, false, true);
                        }

                        if(!\File::exists($saveDirectory)) {
                            $result["error"] = "Directory could not be created.";
                            return $result;
                        }

                        $image->save(($saveDirectory . "\\" . $image_name));
                        $thumb_image->save(($saveDirectory . "\\" . $thumb_image_name));
                    } catch(NotWritableException $e) {
                        $result["error"] = $e->getMessage() . $e->getTraceAsString();
                        return $result;
                    }

                    // Continue
                    $upc_code = $row->getWorksheet()->getCell("C{$count}")->getValue() ?: "";
                    $category = $row->getWorksheet()->getCell("D{$count}")->getValue();
                    $subcategory = $row->getWorksheet()->getCell("E{$count}")->getValue();
                    $brand = $row->getWorksheet()->getCell("F{$count}")->getValue();
                    $variate_by = $row->getWorksheet()->getCell("G{$count}")->getValue();

                    Product::create([
                        "name" => $name,
                        "image_path" => $image_name ?: "",
                        "thumb_image_path" => $thumb_image_name ?: "",
                        "slug" => $slug,
                        "upc_code" => $upc_code,
                        "category_id" => Category::where("name", $category)->first()->id,
                        "subcategory_id" => SubCategory::where("name", $subcategory)->first()->id,
                        "brand_id" => Brand::where("name", $brand)->first()->id,
                        "variate_by" => $variate_by
                    ]);

                    // Record action history
                    $description = "Added new product from Excel document migration";
                    ActionHistory::AddNewActionHistory($description);
                    break;

                case "product_variations":
                    // Check to ensure there is an image path
                    $image_path = $row->getWorksheet()->getCell("B{$count}")->getValue();
                    if($image_path == "" || $image_path == null) {
                        $result["error"] = "All product variations must have an image path!";
                        return $result;
                    }

                    $prefix = str_replace(" ", "-", $name);
                    $image_ext = \File::extension($image_path);
                    $image_name = "{$prefix}.$image_ext";
                    $thumb_image_name = "{$prefix}-thumb.$image_ext";
                    $saveDirectory = public_path("inventory\\products\\" . $name);

                    $manager = new ImageManager(array("driver" => "gd"));
                    $image = $manager->make($image_path)->fit(700, 700);
                    $thumb_image = $manager->make($image_path)->fit(250, 250);

                    // Ensure product name is valid
                    $product_name = $row->getWorksheet()->getCell("C{$count}")->getValue();
                    if(!$product_name) {
                        $result["error"] = "All product variations must have a product name";
                        return $result;
                    }
                    $product = Product::where("name", $product_name);
                    if(!$product) {
                        $result["error"] = "{$product_name} does not match any product";
                        return $result;
                    } else {
                        $product = $product->first();
                    }

                    // Process image. Save to directory.
                    try{
                        if(\File::exists($saveDirectory)) {
                            \File::chmod($saveDirectory, 0775);
                        } else {
                            \File::makeDirectory($saveDirectory, 0775, false, true);
                        }

                        if(!\File::exists($saveDirectory)) {
                            $result["error"] = "Directory could not be created. We suggest that you create the directory and try again. Here is the path: {$saveDirectory}";
                            return $result;
                        }

                        $image->save(($saveDirectory . "\\" . $image_name));
                        $thumb_image->save(($saveDirectory . "\\" . $thumb_image_name));
                    } catch(NotWritableException $e) {
                        $result["error"] = "Cannot write image to the storage path";
                        return $result;
                    }

                    $sku = $row->getWorksheet()->getCell("D{$count}")->getValue();
                    $weight = $row->getWorksheet()->getCell("E{$count}")->getValue();
                    $weight_unit = $row->getWorksheet()->getCell("F{$count}")->getValue();
                    $color = $row->getWorksheet()->getCell("G{$count}")->getValue();
                    $size = $row->getWorksheet()->getCell("H{$count}")->getValue();
                    $stock = $row->getWorksheet()->getCell("I{$count}")->getValue();
                    $stock_threshold = $row->getWorksheet()->getCell("J{$count}")->getValue();
                    $has_batch = $row->getWorksheet()->getCell("K{$count}")->getValue();
                    $unit_cost = $row->getWorksheet()->getCell("L{$count}")->getValue();
                    $retail_price = $row->getWorksheet()->getCell("M{$count}")->getValue();
                    $wholesale_price = $row->getWorksheet()->getCell("N{$count}")->getValue();

                    $variation = ProductVariation::create([
                        "variation_name" => $name,
                        "image_path" => $image_name,
                        "thumb_image_path" => $thumb_image_name,
                        "slug" => $slug,
                        "product_id" => $product->id,
                        "sku" => $sku,
                        "weight" => $weight,
                        "weight_unit" => $weight_unit,
                        "color" => $color,
                        "size" => $size,
                        "stock" => (trim($stock) == "Yes" ? 0 : $stock),
                        "stock_threshold" => $stock_threshold,
                        "reserved_qty" => 0
                    ]);

                    if(trim($has_batch) == "No") {
                        $values = [
                            "batch_code" => Uuid::uuid4(),
                            "variation_id" => $variation->id,
                            "quantity" => $variation->stock,
                            "total_cost" => $stock * $unit_cost,
                            "unit_cost" => $unit_cost,
                            "retail_price" => $retail_price,
                            "wholesale_price" => $wholesale_price ?: $retail_price,
                            "expiry_date" => Carbon::parse("12/05/3030"),
                            "balance_due" => 0.00,
                            "change" => 0.00,
                            "payment_method" => "Cash",
                            "remarks" => Carbon::today()->toDateTimeString()
                        ];

                        (new Batch())->AddNewBatch($values);
                    }

                    // Record action history
                    $description = "Added new variation for {$product->name} from Excel document migration";
                    ActionHistory::AddNewActionHistory($description);
                    break;

                case "batches":
                    // first row is not batch name, rather variation_id
                    $quantity = $row->getWorksheet()->getCell("B{$count}")->getValue();
                    $unit_cost = $row->getWorksheet()->getCell("C{$count}")->getValue();
                    $total_cost = (float)$quantity * (float)$unit_cost;
                    $retail_price = $row->getWorksheet()->getCell("D{$count}")->getValue();
                    $wholesale_price = $row->getWorksheet()->getCell("E{$count}")->getValue();
                    $expiry_date = $row->getWorksheet()->getCell("F{$count}")->getValue();
                    $on_sale = $row->getWorksheet()->getCell("G{$count}")->getValue() == "Yes";

                    $variation = ProductVariation::all()->where("variation_name", $name)->first();
                    if(!$variation) {
                        $result["error"] = "Could not fnd a variation with the name: {$name}";
                        return $result;
                    }

                    $values = [
                        "batch_code" => Uuid::uuid4(),
                        "variation_id" => $variation->id,
                        "quantity" => $quantity,
                        "total_cost" => $total_cost,
                        "unit_cost" => $unit_cost,
                        "retail_price" => $retail_price,
                        "wholesale_price" => $wholesale_price,
                        "on_sale" => intval($on_sale),
                        "expiry_date" => Carbon::createFromTimestamp(Date::excelToDateTimeObject($expiry_date, "Africa/Lagos")->getTimestamp()),
                        "balance_due" => 0.00,
                        "change" => 0.00,
                        "payment_method" => "Cash",
                        "remarks" => Carbon::today()->toDateTimeString()
                    ];

                    (new Batch())->AddNewBatch($values);

                    // Record action history
                    $description = "Added new batch for {$variation->variation_name} from Excel document migration";
                    ActionHistory::AddNewActionHistory($description);

                    break;
            }

            $count++;
        }

        $result["success"] = ucfirst(camel_case($table)) . " have been migrated successfully!";
        return $result;
    }
}
