<?php

namespace App\Http\Controllers;

use App\Models\ActionHistory;
use App\Models\Batch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\DamagedProducts;
use App\Models\ErrorLog;
use App\Models\MeasurementUnit;
use App\Models\POSProduct;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\SubCategory;
use Carbon\Carbon;
use File;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Intervention\Image\Exception\NotWritableException;
use Intervention\Image\ImageManager;
use Response;
use Session;

class ProductController extends Controller {
    /**
     * ProductController constructor.
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function show(Request $request) {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $products = new POSProduct();
            $products = $products->all();

            return view('products.show', compact('products'));
        }

        abort(401, 'You do not have the required authorization.');

        return redirect()->home();
    }

    /**
     ** find a product and its variations using the product sku/barcod.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function barcodeFind(Request $request): JsonResponse {
        $search = $request->get('barcode');
        $variations = null;

        if (!empty($search)) {
            $products = Product::query()->where(static function (Builder $query) use ($search) {
                $query->where('upc_code', 'LIKE', "%$search%")
                    ->orWhere('name', 'LIKE', "%$search%");
            })->get()->toArray();

            $variations = collect([]);
            foreach ($products as $product) {
                $variations->add((new POSProduct())->findByProduct($product['id']));
            }

            return Response::json(['variations' => $variations->toArray()]);
        }

        return Response::json(['product' => 'Barcode is null or empty']);
    }

    /**
     * @return JsonResponse
     */
    public function getPOSProducts(): JsonResponse {
        $products = Product::query()->with(['variations'])->get();

        $variations = collect([]);
        foreach ($products as $product) {
            $items = (new POSProduct)->findByProduct($product->id);

            foreach ($items as $item) {
                $variations->add($item);
            }
        }

        return response()->json([
            'products' => $variations->toArray()
        ]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function create(Request $request) {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $categories = Category::query()->orderBy('name')->get();
            $brands = Brand::query()->orderBy('name')->get();
            $subCategories = SubCategory::query()->orderBy('name')->get();
            $products = Product::all();

            return view('products.create', compact('categories', 'brands', 'subCategories', 'products'));
        }

        abort(401, 'You do not have the required authorization.');

        return redirect()->home();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|unique:products|max:50',
            'product-image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $image = $request->file('product-image');
        $prefix = str_replace(' ', '-', $request['name']);
        $image_name = "{$prefix}.{$image->getClientOriginalExtension()}";
        $thumb_image_name = "{$prefix}-thumb.{$image->getClientOriginalExtension()}";
        $saveDirectory = public_path('inventory' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . $request['name']);

        // resize the image
        $manager = new ImageManager(['driver' => 'gd']);
        $image = $manager->make($image)->fit(700, 700);
        $thumb_image = $manager->make($image)->fit(250, 250);

        try {
            File::exists($saveDirectory) or File::makeDirectory($saveDirectory, 0775, true, true);

            $image->save($saveDirectory . DIRECTORY_SEPARATOR . $image_name);
            $thumb_image->save($saveDirectory . DIRECTORY_SEPARATOR . $thumb_image_name);
        } catch (NotWritableException $e) {
            // Nothing to do
            $stack_trace = $e->getTraceAsString();
            $error_message = $e->getMessage();
            ErrorLog::AddNewError($e, $error_message, $stack_trace);
        }

        // Retrieve data from POST request
        $values = [
            'name' => $request['name'],
            'category' => $request['product-category'],
            'subcategory' => $request['product-subcategory'],
            'brand' => $request['product-brand'],
            'barcode' => $request['product-barcode'] ?: '',
            'image_path' => $image_name,
            'thumb_image_path' => $thumb_image_name,
            'variate_by' => $request['product-variate-by']
        ];

        // Save product to database
        if (Product::addNewProduct($values)) {
            $description = 'Added new product: ' . title_case($values['name']);
            ActionHistory::addNewActionHistory($description);

            session()->flash('success', 'Product has been added!');

            $product = Product::all()->where('name', $request['name'])->first();

            return redirect()->route('editProduct', $product->slug);
        }

        session()->flash('error', 'Product could not be added!');

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse {
        //		$this->validate($request, [
//            "name" => "required|unique:products|max:50",
        //		]);

        // Retrieve data from POST request
        $values = [
            'name' => $request['name'],
            'category' => $request['product-category'],
            'subcategory' => $request['product-subcategory'],
            'brand' => $request['product-brand'],
            'barcode' => $request['product-barcode'] ?: '',
            'variate_by' => $request['product-variate-by'],
            'product_id' => $request['product_id']
        ];

        // Save product to database
        if (Product::updateProduct($values)) {
            $description = 'Updated product: ' . title_case($values['name']);
            ActionHistory::AddNewActionHistory($description);

            session()->flash('success', 'Product has been updated successfully');

            $product = Product::all()->where('name', $request['name'])->first();

            return redirect()->route('editProduct', $product->slug);
        }

        session()->flash('error', 'Product could not be updated!');

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Application|Factory|RedirectResponse|View
     */
    public function edit(Request $request, $slug) {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $categories = Category::query()->orderBy('name')->get();
            $brands = Brand::query()->orderBy('name')->get();
            $subCategories = SubCategory::query()->orderBy('name')->get();
            $product = Product::query()->where('slug', $slug)->first();
            $weight_units = MeasurementUnit::query()->orderBy('name')->get();

            return view('products.edit', compact(
                'categories',
                'brands',
                'subCategories',
                'product',
                'weight_units'
            ));
        }

        abort(401, 'You do not have the required authorization.');

        return redirect()->home();
    }

    /**
     * @param Request $request
     * @param $slug
     * @return RedirectResponse
     */
    public function reserve(Request $request, $slug): RedirectResponse {
        /** @var ProductVariation $product */
        $product = ProductVariation::where('slug', $slug)->firstOrFail();
        if ($request['quantity'] > $product->stock) {
            Session::flash('error', 'You cannot reserve more than the available stock!');
        } else {
            $product->reserved_qty = $request['quantity'];
            $product->save();
            Session::flash('success', "{$request['quantity']} of {$product->variation_name} has added to your reserve.");
        }

        return back();
    }

    /**
     * @param Request $request
     * @param $slug
     * @return RedirectResponse
     */
    public function record_damaged(Request $request, $slug) {
        $cause = $request['cause'] ?: '';
        $description = $request['description'] ?: '';

        /** @var ProductVariation $product */
        $product = ProductVariation::where('slug', $slug)->first();

        $damaged_qty = $request['damaged_quantity'];
        $count = 0;


        if ($damaged_qty > $product->stock) {
            Session::flash('error', 'Damaged items cannot be more than the available stock!');
        } else {
            // find the product batch based on the expiry date
            $expiry_date = Carbon::parse($request['batch']);
            $batches = Batch::all()->where('variation_id', $product->id);

            foreach ($batches as $batch) {
                $count++;
                if (Carbon::parse($batch->expiry_date)->equalTo($expiry_date)) {
                    $batch->current_quantity -= $damaged_qty;
                    $batch->save();

                    // Do same for the product
                    $product->stock -= $damaged_qty;
                    $product->save();

                    DamagedProducts::create([
                        'variation_id' => $product->id,
                        'quantity' => $damaged_qty,
                        'damage_cause' => $cause,
                        'description' => $description
                    ]);

                    Session::flash('success', 'Damages have been recorded');
                    break;
                }

                if ($count === count($batches)) {
                    Session::flash('error', "Could not find a batch of {$product->variation_name} with the given expiry date.");
                }
            }
        }

        return back();
    }

    //
    //
    //  AJAX CONTROLLERS
    //
    //

    /**
     * Get variations for a product of given id.
     *
     * @param $id .   Represents the product id
     * @return JsonResponse
     */
    public function getVariations($id): JsonResponse {
        $product = Product::find($id);

        return Response::json($product->variations);
    }
}
