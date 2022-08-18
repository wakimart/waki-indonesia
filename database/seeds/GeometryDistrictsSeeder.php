<?php

use App\GeometryDistrict;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeometryDistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $geoms = json_decode(file_get_contents(public_path('/geojson/surabaya_kecamatan.geojson')), true);
            $geoms = collect($geoms['features']);
            $geoms = $geoms->map(function($geom) {
                $geom['subdistrict_name'] = $geom['properties']['KETERANGAN'];
                return $geom;
            });
            $geoms = $geoms->keyBy('subdistrict_name')->toArray();
            $districs = \App\RajaOngkir_Subdistrict::where('city_id', 444)->get()->keyBy('subdistrict_name')->toArray();

            if (!array_diff_key($geoms, $districs)) {
                foreach ($districs as $key => $district) {
                    if (isset($geoms[$key])) {
                        $geomDistrict = new GeometryDistrict;
                        $geomDistrict->distric = $district['id'];
                        $geomDistrict->geom = $geoms[$key]['properties']['WKT'];
                        $geomDistrict->save();
                    }
                }
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            echo "Error! " . $ex->getMessage();
        }
    }
}
