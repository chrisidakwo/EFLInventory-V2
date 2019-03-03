<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Dealer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', "phone"
    ];

    public function brands() {
        return $this->belongsToMany(Brand::class, "brand_dealers");
    }

    /**
     * Get all dealers from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll() {
        return Dealer::all();
    }

    /**
     * Add a new dealer to database
     *
     * @param array $values
     * @return bool. Returns true if added successfully; false otherwise
     */
    public function AddNewDealer(array $values) {
        $created = false;
        if(is_string($values["full_name"]) && ($values["full_name"] != "")) {
            $dealer = new Dealer();
            $dealer->full_name = $values["full_name"];
            $dealer->phone = $values["phone"] ?: "";
            $created = $dealer->save();

            // Associate relationship
            $brands = $values["brands"];
            if(!is_array($brands) && is_string($brands)) {
                $brands = explode(",", $brands);
            }

            foreach ($brands as $brand) {
                $b = Brand::whereId($brand)->first();
                if($b) {
                    $dealer->brands()->attach(Brand::find($b->id));
                }
            }
        }

        return $created;
    }

    /**
     * Update an existing dealer
     *
     * @param array $values
     * @return bool
     */
    public function UpdateDealer(array $values) {
        $dealer_id = $values["dealer_id"];
        $full_name = $values["full_name"];
        $brands = $values["brands"];
        $phone = $values["phone"];

        $dealer = new Dealer();
        $dealer = $dealer->getAll()->where("id", $dealer_id)->first();
        $dealer->full_name = $full_name;
        $dealer->phone = $phone ?: "";

        $dealer->brands()->detach();

        foreach ($brands as $brand) {
            /*$brand = BrandDealer::create([
                "brand_id" => $brand,
                "dealer_id" => $dealer->id
            ]);*/

            $dealer->brands()->attach(Brand::find($brand));
        }

        $updated = ($dealer->save()) ?: true;

        return $updated;
    }

    /**
     * Delete a dealer from the database
     *
     * @param $id
     * @return mixed. Returns true if successful; false otherwise; null if dealer doesn't exist
     */
    public function DeleteDealer($id) {
        $deleted = false;
        $dealer = new Dealer();
        $dealer = $dealer->getAll()->where("id", $id)->first();
        $dealer->brands()->detach();

        return $dealer->delete();
    }
}
