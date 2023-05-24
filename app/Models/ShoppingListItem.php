<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingListItem extends Model
{
    protected $table = 'shopping_list_items';

    protected $fillable = ['description', 'quantity', 'amount', 'checked', 'position'];

    public function shoppingList()
    {
        return $this->belongsTo(ShoppingList::class);
    }
}
