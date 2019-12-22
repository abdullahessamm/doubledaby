<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\bill;
use App\order;
use App\orderColor;
use App\orderColorSize;

class BillsController extends Controller
{
    
    function get_all_bills(Request $request)
    {
        if ($request->ajax() && auth()->user()->level === 'admin'):
            $all_bills  = bill::get();
            $all_data   = [];
            foreach ($all_bills as $bill):
                $bill['orders'] = order::where('bill_id', $bill->id)->get();
                foreach ($bill['orders'] as $order):
                    $order['colors'] = orderColor::where('order_id', $order->id)->get();
                    foreach ($order['colors'] as $color):
                        $color['sizes'] = orderColorSize::where('color_id', $color->id)->get();
                    endforeach;
                endforeach;
                array_push($all_data, $bill);
            endforeach;
            foreach ($all_bills as $bill):
                $bill['date'] = strtotime($bill['created_at']);
            endforeach;
            return json_encode($all_data);
        else:
            return view('errors.404');
        endif;
    }

    // Watch changes of an exists bill and edit it.

    function watch_changes(Request $request) 
    {
        if ($request->ajax() && auth()->user()->level === 'admin'):
            //deleting section
            #deleting bills.
            $bills_in_DB = []; //Test
            $deleted_bills_IDs = $request->deleted_items['bills_IDs'];
            foreach ($deleted_bills_IDs as $bill_ID):
                orderColorSize::where('bill_id', $bill_ID)->delete();
                orderColor::where('bill_id', $bill_ID)->delete();
                order::where('bill_id', $bill_ID)->delete();
                bill::where('id', $bill_ID)->delete();
            endforeach;
            #deleting orders
            $deleted_orders_IDs = $request->deleted_items['orders_IDs'];
            foreach ($deleted_orders_IDs as $order_ID):
                orderColorSize::where('order_id', $order_ID)->delete();
                orderColor::where('order_id', $order_ID)->delete();
                order::where('id', $order_ID)->delete();
            endforeach;
            #deleting colors
            $deleted_colors_IDs = $request->deleted_items['colors_IDs'];
            foreach ($deleted_colors_IDs as $color_ID):
                orderColorSize::where('color_id', $order_ID)->delete();
                orderColor::where('id', $color_ID)->delete();
            endforeach;
            #deleting sizes
            $deleted_sizes_IDs = $request->deleted_items['sizes_IDs'];
            foreach ($deleted_sizes_IDs as $size_ID) {
                orderColorSize::where('id', $size_ID)->delete();
            }
            //end of deleting section
            
            //editing section
            $all_bills = $request->bills;
            foreach ($all_bills as $bill):
                $bill_in_db        = bill::where('id', $bill['id'])->get()->first();
                $bill_in_db->total_price != $bill['total_price'] ? $bill_in_db->total_price = $bill['total_price'] : ''; //update total price
                $bill_in_db->save();

                #edit orders of bill
                $bill_orders = $bill['orders'];
                foreach ($bill_orders as $order):
                    $order_in_db = order::where('id', $order['id'])->get()->first();
                    $order_in_db->count != $order['count'] ? $order_in_db->count = $order['count'] : ''; //update count.
                    $order_in_db->total_price != $order['total_price'] ? $order_in_db->total_price = $order['total_price'] : ''; //update total price.
                    $order_in_db->save();
                    
                    #edit colors of order
                    $order_colors = $order['colors'];
                    foreach ($order_colors as $color):
                        $color_in_db = orderColor::where('id', $color['id'])->get()->first();
                        $color_in_db->count != $color['count'] ? $color_in_db->count = $color['count'] : '';
                        $color_in_db->save();

                        #edit sizes of color
                        $color_sizes = $color['sizes'];
                        foreach ($color_sizes as $size):
                            $size_in_db = orderColorSize::where('id', $size['id'])->get();
                            if (count($size_in_db) === 0):
                                //creating new size
                                $created_size = orderColorSize::create([
                                    'color_id' => $size['color_id'],
                                    'order_id' => $size['order_id'],
                                    'bill_id'  => $size['bill_id'],
                                    'count'    => $size['count'],
                                    'size'     => $size['size']
                                ]);
                                return json_encode(['new_size_created' => $created_size, 'old_id' => $size['id']]);
                            else:
                                //edit current size
                                $the_size_in_db = $size_in_db->first();
                                $the_size_in_db->count != $size['count'] ? $the_size_in_db->count = $size['count'] : ''; //update count
                                $the_size_in_db->save();
                            endif;
                        endforeach;
                    endforeach;
                endforeach;
            endforeach;
        endif;
    }
    // calculate the requested day earnings
    public function calc_day_earning(Request $request)
    {
        if ($request->ajax() && auth()->user()->level == 'admin'):
            $all_bills    = bill::get();
            $day_earnings = 0;
            foreach ($all_bills as $bill):
                if (date('Y-m-d', strtotime($bill->created_at) + (2*60*60)) === $request->day):
                    $day_earnings += $bill->total_price;
                endif;
            endforeach;
            return $day_earnings;
        endif;
    }

    //Calculate the requested month earnings
    public function calc_month_earnings(Request $request)
    {
        if ($request->ajax() && auth()->user()->level === 'admin'):
            $all_bills = bill::get();
            $month_earnings = 0;
            foreach ($all_bills as $bill):
                if (date('Y-m', strtotime($bill->created_at)) == $request->month)
                {
                    $month_earnings += $bill->total_price;
                }
            endforeach;
            return $month_earnings;
        endif;
        
    }

    //calculate the requested year earnings
    public function calc_year_earnings(Request $request)
    {
        if ($request->ajax() && auth()->user()->level === 'admin'):
            $all_bills = bill::get();
            $year_earnings = 0;
            foreach ($all_bills as $bill):
                date('Y', strtotime($bill->created_at)) == $request->year ? $year_earnings += $bill->total_price : '';
            endforeach;
            return $year_earnings;
        endif;
    }

    //Find The best customers from bills
    public function special_customers(Request $request)
    {
        if ($request->ajax() && auth()->user()->level === 'admin'):
            $all_bills = $request->wanted === 'members' ? bill::where('is_member', '1')->get() : bill::where('is_member', '0')->get();
            $clients = [];
            foreach ($all_bills as $bill):
                $total_payments = 0;
                $client_found = false;
                foreach ($clients as $index => $client):
                    if ($client['name'] === $bill->customer_name):
                        $total_payments = $client['total_payments'] + $bill->total_price;
                        $clients[$index]['total_payments'] = $total_payments;
                        $client_found = true;
                    endif;
                endforeach;
                if (!$client_found) {
                    array_push($clients, [
                        "name"              => $bill->customer_name,
                        "total_payments"    => $bill->total_price
                    ]);
                }//end of if
            endforeach;
            arsort($clients);
            //get top 4 clients
            $top_4_clients = [];
            count($clients) <= 4 ? $top_4_clients = $clients : $top_4_clients = array_slice($clients, 0, 4);
            return $top_4_clients;
        endif;
    }
}
