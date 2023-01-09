<?php

use App\GeometryDistrict;
use App\Region;
use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regionSeeders = [
            ["id" => 1, "name" => "Surabaya Pusat", "bg_color" => "#f8cbdc", "districts" => [6133, 6137, 6152, 6157]],
            ["id" => 2, "name" => "Surabaya Utara", "bg_color" => "#fbeea9", "districts" => [6134, 6142, 6143, 6146, 6151]],
            ["id" => 3, "name" => "Surabaya Timur", "bg_color" => "#c5dca4", "districts" => [6138, 6139, 6145, 6148, 6153, 6155, 6158]],
            ["id" => 4, "name" => "Surabaya Selatan", "bg_color" => "#a3d6e9", "districts" => [6135, 6136, 6140, 6141, 6150, 6159, 6160, 6161]],
            ["id" => 5, "name" => "Surabaya Barat", "bg_color" => "#cdc9df", "districts" => [6131, 6132, 6144, 6147, 6149, 6154, 6156]],

            ["id" => 6, "name" => "Bandung", "bg_color" => "#808080", "districts" => \App\RajaOngkir_Subdistrict::whereIn('city_id', [22, 23, 24])->get()->pluck('subdistrict_id')->toArray()],
            ["id" => 7, "name" => "Malang", "bg_color" => "#FF00FF", "districts" => \App\RajaOngkir_Subdistrict::whereIn('city_id', [255, 256])->get()->pluck('subdistrict_id')->toArray()],
            ["id" => 8, "name" => "Jakarta Selatan", "bg_color" => "#000080", "districts" => \App\RajaOngkir_Subdistrict::whereIn('city_id', [153])->get()->pluck('subdistrict_id')->toArray()],
            ["id" => 9, "name" => "Jakarta Utara", "bg_color" => "#00FFFF", "districts" => \App\RajaOngkir_Subdistrict::whereIn('city_id', [155])->get()->pluck('subdistrict_id')->toArray()],
            ["id" => 10, "name" => "Jakarta Barat", "bg_color" => "#964B00", "districts" => \App\RajaOngkir_Subdistrict::whereIn('city_id', [151])->get()->pluck('subdistrict_id')->toArray()],
            ["id" => 11, "name" => "Jakarta Timur", "bg_color" => "#FFD700", "districts" => \App\RajaOngkir_Subdistrict::whereIn('city_id', [154])->get()->pluck('subdistrict_id')->toArray()],
            ["id" => 12, "name" => "Jakarta Pusat", "bg_color" => "#660000", "districts" => \App\RajaOngkir_Subdistrict::whereIn('city_id', [152])->get()->pluck('subdistrict_id')->toArray()],
            ["id" => 13, "name" => "Tangerang", "bg_color" => "#808000", "districts" => \App\RajaOngkir_Subdistrict::whereIn('city_id', [455, 456, 457])->get()->pluck('subdistrict_id')->toArray()],
        ];

        foreach ($regionSeeders as $regionSeeder){
            $region = Region::find($regionSeeder['id']);
            if (! $region) {
                $region = new Region;
                $region->id = $regionSeeder['id'];
                $region->name = $regionSeeder['name'];
                $region->bg_color = $regionSeeder['bg_color'];
                $region->save();
    
                GeometryDistrict::whereIn('distric', $regionSeeder['districts'])
                    ->update(['region_id' => $region->id]);
            }
        }
    }
}
