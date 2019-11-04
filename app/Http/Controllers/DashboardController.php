<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use App\Colors;
use App\Sizes;

class DashboardController extends Controller
{
    public function add_product(Request $request)
    {
        if (auth()->user()->level === 'admin' && $request->ajax())
        {
            $name               =   $request->name;
            $company            =   $request->company;
            $price              =   $request->price;
            $for                =   $request->for;
            $colorsAndSizes     =   $request->colorsAndSizes;
            $product_count      =   0;

            $product = Products::create([
                'name'      =>  $name,
                'company'   =>  $company,
                'price'     =>  $price,
                'for'       =>  $for
            ]);
            
            foreach ($colorsAndSizes as $color) {
                $color_name  = $color['color'];
                $color_count = $color['count'];
                $color_sizes = $color['sizes'];

                $product_count += $color_count;
                
                $color_data = Colors::create([
                    'product_id'    =>  $product->id,
                    'color'         =>  $color_name,
                    'count'         =>  $color_count
                ]);

                foreach ($color_sizes as $size) {
                    $the_size   = $size['size'];
                    $size_count = $size['count'];
                    
                    Sizes::create([
                        'size'      =>  $the_size,
                        'count'     =>  $size_count,
                        'color_id'  =>  $color_data->id,
                    ]);
                };
            }

            $product->count = $product_count;
            $product->save();

            return json_encode([
                'status'        => 'success',
                'product_id'    => $product->id,
                'product_count' => $product->count
            ]);

        }
    }

    public function delete_product(Request $request)
    {
        if ($request->ajax() && auth()->user()->level === 'admin')
        {
            $colors = Colors::where('product_id', $request->id)->get();

            foreach ($colors as $color) {
                $color_id = $color->id;
                Sizes::where('color_id', $color_id)->delete();
                $color->delete();
            }

            Products::where('id', $request->id)->delete();

            return json_encode([
                'status' => 'success'
            ]);

        }
    }

    public function get_products(Request $request)
    {
        //sleep(10);
        
        $all_data = [];
        $all_products = Products::get();
        
        foreach ($all_products as $product)
        {
            $product_data = [
                'id'            =>  $product->id,
                'name'          =>  $product->name,
                'company'       =>  $product->company,
                'count'         =>  $product->count,
                'for'           =>  $product->for,
                'price'         =>  $product->price,
                'created_at'    =>  strtotime($product->created_at),
                'updated_at'    =>  strtotime($product->updated_at),
                'colors'        =>  []

            ];

            $product_colors = Colors::where('product_id', $product_data['id'])->get();
            foreach ($product_colors as $color)
            {
                $color_data = [
                    'id'            =>  $color->id,
                    'product_id'    =>  $color->product_id,
                    'color'         =>  $color->color,
                    'count'         =>  $color->count,
                    'created_at'    =>  strtotime($color->created_at),
                    'updated_at'    =>  strtotime($color->updated_at),
                    'sizes'         =>  []
                ];

                $color_sizes = Sizes::where('color_id', $color_data['id'])->get();
                foreach ($color_sizes as $size)
                {
                    $size_data = [
                        'id'            =>  $size->id,
                        'color_id'      =>  $size->color_id,
                        'size'          =>  $size->size,
                        'count'         =>  $size->count,
                        'created_at'    =>  strtotime($size->created_at),
                        'updated_at'    =>  strtotime($size->updated_at),
                    ];
                    array_push($color_data['sizes'], $size_data);
                }
                array_push($product_data['colors'], $color_data);
            }
            array_push($all_data, $product_data);
        }

        return json_encode($all_data);
            
    }

    public function get_product_by_id($id=null)
    {
        if ($id):
            $product = Products::where('id', $id)->get()->first();
            $colors  = Colors::where('product_id', $id)->get();
            $product_data = [
                'name'      =>  $product->name,
                'company'   =>  $product->company,
                'count'     =>  $product->count,
                'price'     =>  $product->price,
                'gender'    =>  $product->for,
                'colors'    =>  [],
            ];
            
            foreach ($colors as $color)
            {
                $color_data = [
                    'id'    =>  $color->id,
                    'color' =>  $color->color,
                    'count' =>  $color->count,
                    'sizes' =>  []
                ];
                $color_sizes = Sizes::where('color_id', $color_data['id'])->get();
                foreach ($color_sizes as $size)
                {
                    $size_data = [
                        'id'    =>  $size->id,
                        'size'  =>  $size->size,
                        'count' =>  $size->count,
                    ];
                    array_push($color_data['sizes'], $size_data);
                }
                array_push($product_data['colors'], $color_data);
            }
            return json_encode($product_data);
        endif;
    }

    public function edit_product(Request $request)
    {
        if ($request->ajax() && auth()->user()->level === 'admin')
        {
            $deleted_colors = $request->deleted_colors;
            $deleted_sizes  = $request->deleted_sizes;

            foreach ($deleted_colors as $color_id)
            {
                $color_sizes = Sizes::where('color_id', $color_id)->get();
                foreach ($color_sizes as $size)
                {
                    $size->delete();
                }
            }

            Colors::whereIn('id', $deleted_colors)->delete();
            Sizes::whereIn('id', $deleted_sizes)->delete();
            
            $product_database = Products::where('id', $request->id)->get();
            if (count($product_database) !== 0):
                $product = $product_database->first();
                $product->name      = $request->name;
                $product->company   = $request->company;
                $product->count     = $request->count;
                $product->price     = $request->price;
                $product->for       = $request->gender;
                $product->save();
                
                $request_colors = $request->colors;
                foreach ($request_colors as $request_color)
                {
                    if (!isset($request_color['new'])):
                        $database_color = Colors::where('id', $request_color['id'])->get()->all();
                        $database_color = $database_color[0];
                        $database_color->color = $request_color['color'];
                        $database_color->count = $request_color['count'];
                        $database_color->save();
                    else:
                        $database_color = Colors::create([
                            'color'      =>  $request_color['color'],
                            'count'      =>  $request_color['count'],
                            'product_id' =>  $request->id,
                        ]);
                    endif;
                    
                    $request_sizes = $request_color['sizes'];
                    foreach ($request_sizes as $request_size)
                    {
                        if (!isset($request_size['new'])):
                            $database_size = Sizes::where('id', $request_size['id'])->get()->all();
                            $database_size_data = $database_size[0];
                            $database_size_data->size = $request_size['size'];
                            $database_size_data->count = $request_size['count'];
                            $database_size_data->save();
                        else:
                            Sizes::create([
                                'size'      => $request_size['size'],
                                'color_id'  => $database_color->id,
                                'count'     => $request_size['count']
                            ]);
                        endif;
                    }
                }
            endif;
            $data = [
                'product_data' => $product_database->first(),
                'created_at'   => strtotime($product_database->first()->created_at),
                'updated_at'   => strtotime($product_database->first()->updated_at)
            ];

            return json_encode($data);
        }
    }

}
