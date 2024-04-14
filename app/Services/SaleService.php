<?php
namespace App\Services;
use Auth;
use Session;
use App\Models\Sale;
use App\Models\Product;
use App\User;
use Illuminate\Support\Facades\Http;


class SaleService {

	public function saveSale($data, $request)
	{


                $productName = $data['productName'];
                $product_id = $data['product_id'];
                $unit = $data['unit'];
                $cost = $data['cost'];
                $calculatedCost =$data['calculatedCost'];
                $selling_price = $data['selling_price'];
                $quantity = $data['quantity'];
                $amount = $data['amount'];
                $oldquanity = $data['productOldQty'];

                $totalSaleQty = 0;
                foreach ($quantity as $qtyKey => $qtyVal) {
                	$totalSaleQty = $data['quantity'][$qtyKey] + $totalSaleQty;

                }

                $saleProductsAddons = [];

                for ($i = 0; $i < count($quantity); $i++) {
                	$saleProductsAddons[] = array(
                		'productName' => $productName[$i],
                		'product_id' => $product_id[$i],
                		'unit' => $unit[$i],
                		'cost' => $cost[$i],
                		'calculatedCost' => $calculatedCost[$i],
                		'selling_price' => $selling_price[$i],
                		'quantity' => $quantity[$i],
                		'amount' => $amount[$i],
                		'oldquanity' => $oldquanity[$i],
                	);
                }
                $sale = new Sale;
                $sale->invoice_no = $data['invoice_no'];
                $sale->date = date('Y-m-d',strtotime($data['date']));
                $sale->customer_id = $data['customer_id'];
                $sale->area_id = $data['area_id'];
                $sale->description = $data['description'];
                $sale->discount = $data['discount'];
                $sale->amount = $data['total_amount'];
                $sale->items_addon = serialize($saleProductsAddons);
                $sale->total_qty = $totalSaleQty;
                $sale->status = 1;
                $sale->added_by = Auth::user()->id;

                // echo "<pre>"; print_r($sale); exit();
                $sale->save();

            }
    /*==============================================================*/
    public function updateSaleStock($data, $request)
    {
    	foreach ($data['productName'] as $i => $newData) {
    		$product_id = $data['product_id'][$i];
    		$productName = $data['productName'][$i];
    		$unit = $data['unit'][$i];
    		$quantity = $data['quantity'][$i]; // New Qty...

    		$selectSingleProduct = Product::where('id',$product_id)->first();

    		if ($selectSingleProduct['id'] == $product_id) {

    			$oldquanity = $selectSingleProduct['quantity']; // Old Qty...

    			$newQty = ((int)$oldquanity) - ((int)$quantity);

    			$selectSingleProduct->quantity = $newQty; // Updated Qty...
    			$selectSingleProduct->save();
    		}

    	}
    }
    /*==============================================================*/
    public function updateSale($data, $request, $id)
    {
        /*Check Stock Start*/
                foreach ($data['productName'] as $i => $newData) {
                    $product_id = $data['product_id'][$i];
                    $productName = $data['productName'][$i];
                    $quantity = $data['quantity'][$i]; // new Quantity...
                    $selectSingleProduct = Product::where('id',$product_id)->first();
                    $stockQty = $selectSingleProduct['quantity'];
                        if ($selectSingleProduct['id']==$product_id) {
                            if ($stockQty<$quantity) {
                                return redirect()->back()->with('flash_message_error',$productName.' Product is '.$stockQty.' remaining!');
                            }
                        }
                    }
                    /*Check Stock Ends*/

                $productName = $data['productName'];
                $product_id = $data['product_id'];
                $unit = $data['unit'];
                $cost = $data['cost'];
                $calculatedCost =$data['calculatedCost'];
                $selling_price = $data['selling_price'];
                $quantity = $data['quantity'];
                $amount = $data['amount'];
                $oldquanity = $data['productOldQty'];

                $totalSaleQty = 0;
                foreach ($quantity as $qtyKey => $qtyVal) {
                    $totalSaleQty = $data['quantity'][$qtyKey] + $totalSaleQty;

                }

                $saleProductsAddons = [];

                for ($i = 0; $i < count($quantity); $i++) {
                    $saleProductsAddons[] = array(
                        'productName' => $productName[$i],
                        'product_id' => $product_id[$i],
                        'unit' => $unit[$i],
                        'cost' => $cost[$i],
                        'calculatedCost' => $calculatedCost[$i],
                        'selling_price' => $selling_price[$i],
                        'quantity' => $quantity[$i],
                        'amount' => $amount[$i],
                        'oldquanity' => $oldquanity[$i],
                    );
                }
                $editSale = Sale::find($id);
                $editSale->invoice_no = $data['invoice_no'];
                $editSale->date = date('Y-m-d',strtotime($data['date']));
                $editSale->customer_id = $data['customer_id'];
                $editSale->area_id = $data['area_id'];
                $editSale->description = $data['description'];
                $editSale->discount = $data['discount'];
                $editSale->amount = $data['total_amount'];
                $editSale->items_addon = serialize($saleProductsAddons);
                $editSale->total_qty = $totalSaleQty;
                $editSale->status = $data['status'];
                $editSale->added_by = Auth::user()->id;
                $editSale->save();

    }
    /*==============================================================*/
    public function cancelSale($data, $request ,$id)
    {
        foreach ($data['productName'] as $i => $newData) {
            $product_id = $data['product_id'][$i];
            $productName = $data['productName'][$i];
            $unit = $data['unit'][$i];
            $quantity = $data['quantity'][$i];
            $productQty = $data['productOldQty'][$i];
            $getCurrentSale = Sale::where('id',$id)->first();

            $currenSaleVariations = unserialize($getCurrentSale->items_addon);
            $selectSingleProduct = Product::where('id',$product_id)->first();
            foreach ($currenSaleVariations as $variation) {
                if ($selectSingleProduct['id'] == $variation['product_id']) {
                    $currentQty = $variation['quantity'];
                    $oldquanity = $selectSingleProduct['quantity'];
                    $newQty = ((int)$oldquanity) + ((int)$currentQty);
                    $selectSingleProduct->quantity = $newQty;
                    $selectSingleProduct->save();
                }
            }
        }
    }
    /*==============================================================*/
    public function saleStockPlusWithID($data, $request,$id)
    {
        foreach ($data['productName'] as $i => $newData) {
            $product_id = $data['product_id'][$i];
            $productName = $data['productName'][$i];
            $unit = $data['unit'][$i];
            $quantity = $data['quantity'][$i];
            $getCurrentSale = Sale::where('id',$id)->first();
            $currenSaleVariations = unserialize($getCurrentSale->items_addon);
            $selectSingleProduct = Product::where('id',$product_id)->first();

            foreach ($currenSaleVariations as $variation) {
                if ($selectSingleProduct['id'] == $variation['product_id']) {
                    $oldquanity = $selectSingleProduct['quantity'];
                    $newQty = ($oldquanity - (int)$quantity);
                    $selectSingleProduct->quantity = $newQty;
                    $selectSingleProduct->save();
                }
            }

        }

    }
}
