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
            $arrGeoms = [
                [ //Surabaya
                    "city_id" => [444],
                    "city_geom" => public_path('/geojson/surabaya_kecamatan.geojson'),
                ],
                [ //Bandung
                    "city_id" => [22, 23, 24],
                    "city_geom" => public_path('/geojson/bandung_kecamatan.geojson'),
                ],
                [ //Malang
                    "city_id" => [255, 256],
                    "city_geom" => public_path('/geojson/malang_kecamatan.geojson'),
                ],
                [ //Jakarta
                    "city_id" => [153, 155, 151, 154, 152],
                    "city_geom" => public_path('/geojson/jakarta_kecamatan.geojson'),
                ],
                [ //Tangerang
                    "city_id" => [455, 456, 457],
                    "city_geom" => public_path('/geojson/tangerang_kecamatan.geojson'),
                ],
                [ //Semarang
                    "city_id" => [398, 399],
                    "city_geom" => public_path('/geojson/semarang_kecamatan.geojson'),
                ],
            ];

            foreach ($arrGeoms as $geoms) {
                $geoms['city_geom'] = json_decode(file_get_contents($geoms['city_geom']), true);
                $city_geoms = collect($geoms['city_geom']['features']);
                $city_geoms = $city_geoms->map(function($geom) {
                    $geom['subdistrict_name'] = $geom['properties']['KETERANGAN'];
                    return $geom;
                });
                $city_geoms = $city_geoms->keyBy('subdistrict_name')->toArray();

                $districs = \App\RajaOngkir_Subdistrict::whereIn('city_id', $geoms['city_id'])
                    ->get()->keyBy('subdistrict_name')->toArray();

                if (!array_diff_key($city_geoms, $districs)) {
                    foreach ($districs as $key => $district) {
                        $geomDistrict = GeometryDistrict::where('distric', $district['id'])->first() ?? new GeometryDistrict;
                        if ($geomDistrict->geom == null || $geomDistrict->geom == 'POLYGON()') {
                            $geomDistrict->distric = $district['id'];
                            $geomDistrict->geom = $city_geoms[$key]['properties']['WKT'] ?? 'POLYGON()';
                            $geomDistrict->save();
                        }
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
