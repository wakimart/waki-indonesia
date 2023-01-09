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
            $districs = \App\RajaOngkir_Subdistrict::whereIn('city_id', [
                    444, //Surabaya
                    22, 23, 24, //Bandung
                    255, 256, //Malang
                    153, //Jakarta Selatan
                    155, //Jakarta Utara
                    151, //Jakarta Barat
                    154, //Jakarta Timur
                    152, //Jakarta Pusat
                    455, 456, 457, //Tangerang
                ])
                ->get()->keyBy('subdistrict_name')->toArray();

            if (!array_diff_key($geoms, $districs)) {
                foreach ($districs as $key => $district) {
                    $geomDistrict = GeometryDistrict::where('distric', $district['id'])->first() ?? new GeometryDistrict;
                    if ($geomDistrict->geom == null || $geomDistrict->geom == 'POLYGON()') {
                        $geomDistrict->distric = $district['id'];
                        $geomDistrict->geom = $geoms[$key]['properties']['WKT'] ?? 'POLYGON()';
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
