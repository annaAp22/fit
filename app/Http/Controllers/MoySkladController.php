<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\MoySklad\Ms;
use App\Models\MsProduct;
use App\Models\MsCronCounter;
use Carbon\Carbon;

class MoySkladController extends Controller
{
    // Direction: site -> moysklad.
    // Exports new orders.
    // Old name: cron.
    public function exportOrders()
    {

    }

    // Direction: moysklad -> site.
    // Updates prices and stock.
    // Old name: cron2.
    public function updatePriceAndStock()
    {

    }

    // Direction: moysklad -> site.
    // Imports new products by portion of 1000, updates old data in moysklad products table, site products table stays unchanged.
    // Old name: cron3.
    public function importProducts(Ms $ms)
    {
        // Current import start index and total count
        $counter = MsCronCounter::importProducts()->firstOrFail();

        if( ($counter->total > $counter->start) || ($counter->start == 0 && $counter->total == 0) )
        {
            $paramsString = http_build_query([
                'offset' => $counter->offset,
                'limit'  => $counter->limit,
//                'updatedFrom' => $counter->updated_at->toDateTimeString(),
            ]);

            $products = [];

            if( $res = $ms->importProducts($paramsString) )
            {
                $products = array_merge($products, $res->rows);
                // If products total count > limit by one request,
                // then do another request with offset
                for( $offset = $res->meta->limit; $offset < $res->meta->size; $offset = $offset + $res->meta->limit )
                {
                    $paramsString = http_build_query([
                        'offset' => $offset,
                        'limit'  => $counter->limit,
//                        'updatedFrom' => $counter->updated_at->toDateTimeString(),
                    ]);
                    if( $res = $ms->importProducts($paramsString) )
                    {
                        $products = array_merge($products, $res->rows);
                    }

                }
            }

            // Set last update date
            $counter->updated_at = Carbon::now();
            $counter->save();


            // Insert or update
            dd($products);

            //

        }
        else
        {
            // Set start and total = 0
            $counter->resetImportProducts();
        }

    }

    // Direction moysklad -> site.
    // Imports customers from moysklad
    // Old name: cron4.
    public function importCustomers()
    {

    }

    // Direction moysklad -> site.
    // Updates reservation option status.
    public function updateReservation()
    {

    }

}
