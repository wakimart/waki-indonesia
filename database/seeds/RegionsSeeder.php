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
        ];

        foreach ($regionSeeders as $regionSeeder){
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
