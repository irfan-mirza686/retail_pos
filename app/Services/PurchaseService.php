<?php 
namespace App\Services;
use Auth;
use Session;
use App\Models\Purchase;
use App\Models\Product;
use App\User;
use Illuminate\Support\Facades\Http;


class PurchaseService { 

	/*==============================================================*/
    public function savePurchase($data, $request){
        $productName = $data['productName'];
        $product_id = $data['product_id'];
        $unit = $data['unit'];
        $price = $data['price'];
        $quantity = $data['quantity'];
        $amount = $data['item_amount'];
        $stockQty = $data['stockQty'];

        $purchaseAddonsArray = [];

        for ($i = 0; $i < count($quantity); $i++) {
            $purchaseAddonsArray[] = array(
                'productName' => $productName[$i],
                'product_id' => $product_id[$i],
                'unit' => $unit[$i],
                'price' => $price[$i],
                'quantity' => $quantity[$i],
                'amount' => $amount[$i],
                'stockQty' => $stockQty[$i],
            );
        }
        $purchase = new Purchase;
        $purchase->purchase_no = $data['purchase_no'];
        $purchase->supplier_id = $data['supplier_id'];
        $purchase->date = date('Y-m-d',strtotime($data['date']));
        $purchase->description = $data['description'];
        $purchase->items_addon = serialize($purchaseAddonsArray);
        $purchase->discount = $data['discount'];
        $purchase->amount = $data['amount'];
        $purchase->status = 'received';
        $purchase->added_by = Auth::user()->id;
        $purchase->save();
    }
    /*==============================================================*/
    public function updatePurchaseStock($data, $request){
    	foreach ($data['productName'] as $i => $newData) {
    		$product_id = $data['product_id'][$i];
    		$productName = $data['productName'][$i];

    		$quantity = $data['quantity'][$i]; // New Qty...

    		$selectSingleProduct = Product::where('id',$product_id)->first();
    		if ($selectSingleProduct['id'] == $product_id) {
    			$oldquanity = $selectSingleProduct['quantity']; // Old Qty...
    			$newQty = $oldquanity + $quantity; // Updated New Qty..
    			$selectSingleProduct->quantity = $newQty; 
    			$selectSingleProduct->save();

    		}
    	}

    }
                /*==============================================================*/
    public function updatePurchase($data, $request, $id){
    	/* Without Stock Update Functionality Here*/
    	$productName = $data['productName'];
    	$product_id = $data['product_id'];
    	$unit = $data['unit'];
    	$price = $data['price'];
    	$quantity = $data['quantity'];
    	$amount = $data['item_amount'];
    	$stockQty = $data['stockQty']; // Old Qty...

    	$purchaseAddonsArray = [];

    	for ($i = 0; $i < count($quantity); $i++) {
    		$purchaseAddonsArray[] = array(
    			'productName' => $productName[$i],
    			'product_id' => $product_id[$i],
    			'unit' => $unit[$i],
    			'price' => $price[$i],
    			'quantity' => $quantity[$i],
    			'amount' => $amount[$i],
    			'stockQty' => $stockQty[$i], // Old Qty...
    		);
    	}
    	$updatePurchase = Purchase::find($id);
    	$updatePurchase->purchase_no = $request->purchase_no;
    	$updatePurchase->supplier_id = $request->supplier_id;
    	$updatePurchase->date = date('Y-m-d',strtotime($request->date));
    	$updatePurchase->description = $request->description;
    	$updatePurchase->items_addon = serialize($purchaseAddonsArray);
    	$updatePurchase->amount = $request->amount;
    	$updatePurchase->status = $request->status;
    	$updatePurchase->added_by = Auth::user()->id;
    	$updatePurchase->save();
    }
    /*==============================================================*/
    public function cancelPurchase($data, $request, $purchase_id){

    	foreach ($data['productName'] as $i => $newData) {
    		$product_id = $data['product_id'][$i];
    		$productName = $data['productName'][$i];
    		$unit = $data['unit'][$i];
    		$quantity = $data['quantity'][$i];  // New Qty ..
    		$productQty = $data['stockQty'][$i]; // Old Qty ...
    		$getCurrentPurchase = Purchase::where('id',$purchase_id)->first();

    		$currentPurchaseVariation = unserialize($getCurrentPurchase->items_addon);
    		$selectSingleProduct = Product::where('id',$product_id)->first();
    		foreach ($currentPurchaseVariation as $variation) {
    			if ($selectSingleProduct['id'] == $variation['product_id']) {
    				$currentQty = $variation['quantity'];
    				$oldquanity = $selectSingleProduct['quantity']; // Old Qty...
    				$newQty = ((int)$oldquanity) - ((int)$currentQty); // Updated New Qty..
    				$selectSingleProduct->quantity = $newQty;
    				$selectSingleProduct->save();
    			}
    		}
    	}
    }
    /*==============================================================*/
    public function purchaseStockUpdateWithID($data, $request,$id) {
    	foreach ($data['productName'] as $i => $newData) {
    		$product_id = $data['product_id'][$i];
    		$productName = $data['productName'][$i];
    		$unit = $data['unit'][$i];
    		$quantity = $data['quantity'][$i]; // New Qty ...
    		$getCurrentPurchase = Purchase::where('id',$id)->first();
    		$currenSaleVariations = unserialize($getCurrentPurchase->items_addon);
    		$selectSingleProduct = Product::where('id',$product_id)->first();

    		foreach ($currenSaleVariations as $variation) {
    			if ($selectSingleProduct['id'] == $variation['product_id']) {
    				$oldquanity = $selectSingleProduct['quantity']; // Old Qty ... 
    				$newQty = ($oldquanity + (int)$quantity); // Updated New Qty...
    				$selectSingleProduct->quantity = $newQty; 
    				$selectSingleProduct->save();
    			}
    		}

    	}
    }
}