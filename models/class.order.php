<?php

class order
{
	
	public $user = NULL;
	public $items = array();
	public $subtotal = NULL;
	public $tax = NULL;
	public $total = NULL;
	
	public function changeUser($newuser)
	{
		$this->user = $newuser;
	}
	
	public function addItem($item)
	{
		array_push($this->items, $item);
	}
	
	public function countItems()
	{
		return count($this->items);
	}
	
	public function calcTotal()
	{
		$subtotal = 0.0;
		foreach($this->items as $item)
		{
			$subtotal += $item->price;
		}
		
		$this->subtotal = $subtotal;
		$this->tax = round(($this->subtotal * .06), 2);
		$this->total = $this->subtotal + $this->tax;
		
	}
	
	public function calories()
	{
		$cals = 0;
		foreach($this->items as $item)
		{
			$cals += $item->calories;
		}
		
		return $cals;
	}
	
	public function placeOrder()
	{
		global $pdo;
		
		$stmt = $pdo->prepare("INSERT INTO customerorder (customerId, ordertime, subtotal, tax, ordertotal) VALUES (:customer, :time, :subtotal, :tax, :total)");
		$stmt->execute(array("customer" => $this->user, "time" => date("Y-m-d H:i:s"), "subtotal" => $this->subtotal, "tax" => $this->tax, "total" => $this->total));
		

		$orderid = $pdo->lastInsertId();
		foreach($this->items as $item)
		{
			$stmt = $pdo->prepare("INSERT INTO orderitem (orderId, menuitemId, price, size) VALUES (:order, :item, :price, :size)");
			$stmt->execute(array("order" => $orderid, "item" => $item->itemid, "price" => $item->price, "size" => $item->size));
			
			$itemid = $pdo->lastInsertId();
			foreach($item->options as $option)
			{
				$stmt = $pdo->prepare("INSERT INTO orderitemcomponent (orderitemId, componentitemId) VALUES (:item, :comp)");
				$stmt->execute(array("item" => $itemid, "comp" => $option));
			}
		}
	}
	
	public function closeOrder()
	{
		destroySession("order");
	}
}

?>