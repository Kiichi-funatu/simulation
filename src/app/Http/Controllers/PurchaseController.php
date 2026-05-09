<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function index($id)
    {
        $item = Item::findOrFail($id);

        return view('purchase.index', compact('item'));
    }
}
