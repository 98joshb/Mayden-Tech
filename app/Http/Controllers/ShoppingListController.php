<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Services\TescoAPI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ShoppingListController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'quantity' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        $user = Auth::user();

        $shoppingList = ShoppingList::where('user_id', $user->id)->first();

        if (!$shoppingList) {
            $shoppingList = ShoppingList::create([
                'user_id' => $user->id,
            ]);
        }

        $nextPosition = $shoppingList->items()->max('position') + 1;
        $shoppingList->items()->create([
            'description' => $request->description,
            'quantity' => $request->quantity,
            'amount' => $request->amount,
            'position' => $nextPosition,
        ]);

        return redirect()->route('shopping_list.index');
    }

    public function index()
    {
        $user = Auth::user();
        $items = ShoppingList::with(['items' => function ($query) {
            $query->orderBy('position', 'asc');
        }])->where('user_id', $user->id)->get();
    
        $totalAmount = 0;
        foreach ($items as $shoppingList) {
            foreach ($shoppingList->items as $item) {
                $totalAmount += $item->amount;
            }
        }

        if (isset($user->spending_limit) && $totalAmount > $user->spending_limit) {
            $alertMessage = 'Total amount exceeds spending limit!';
            $alertClass = 'alert-danger';
            return view('shopping_list.display', compact('items', 'totalAmount', 'alertMessage', 'alertClass'));
        } else {
            return view('shopping_list.display', compact('items', 'totalAmount'));
        }
    }

    public function destroy($id)
    {
        $item = ShoppingListItem::findOrFail($id);
        $shoppingList = $item->shoppingList;

        $item->delete();

        // Check if the shopping list is empty
        if ($shoppingList->items()->count() === 0) {
            $shoppingList->delete();
        }

        return redirect()->route('shopping_list.index');
    }

    public function checkItem(Request $request, $id)
    {
        $item = ShoppingListItem::findOrFail($id);
        $item->update(['checked' => !$item->checked]);

        // Check if the shopping list is empty
        $shoppingList = $item->shoppingList;
        if ($shoppingList->items()->count() === 0) {
            $shoppingList->delete();
        }

        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $itemIds = $request->input('itemIds');
        foreach ($itemIds as $index => $itemId) {
            $item = ShoppingListItem::findOrFail($itemId);
            $item->position = $index + 1;
            $item->save();
        }

        return response()->json(['success' => true]);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'recipient_email' => 'required|email', 
            'message' => 'nullable|string',
            'shopping_list_id' => 'required|exists:shopping_lists,id',
        ]);
    
        $shoppingList = ShoppingList::findOrFail($request->shopping_list_id);
    
        $totalAmount = 0;
        foreach ($shoppingList->items as $item) {
            $totalAmount += $item->amount;
        }
        
    
        $recipientEmail = $request->recipient_email;
        $message = $request->message;
    
        $data = [
            'shoppingList' => $shoppingList,
            'messages' => $message,
            'totalAmount' => $totalAmount,
        ];
    
        try {
            Mail::send('emails.shopping_list', $data, function ($message) use ($recipientEmail) {
                $message->to($recipientEmail)
                    ->subject('Your Shopping List');
            });
    
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    
    
}
