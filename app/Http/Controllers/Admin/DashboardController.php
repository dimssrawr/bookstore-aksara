<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        
        $pendingOrders = Order::where('status', 'pending')->count();
        $unreadMessages = Contact::where('is_read', false)->count();

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $recentMessages = Contact::latest()->take(5)->get();
        $lowStockBooks = Book::where('stock', '<=', 5)->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalCategories',
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'unreadMessages',
            'recentOrders',
            'recentMessages',
            'lowStockBooks'
        ));
    }
}
