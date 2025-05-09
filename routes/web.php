<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Models\User;
use App\Models\Order;
use App\Models\News;
use Illuminate\Http\Request;

Route::get('/', function () {
    $user = User::all();
    return view('welcome', compact('user'));
})->name('/');

use Carbon\Carbon;

Route::get('/admin', function (Request $request) {
    $startDate = $request->query('start_date', '2025-01-01');
    $endDate = $request->query('end_date', now()->toDateString());

    $user = User::all();
    $order = Order::whereBetween('created_at', [$startDate, $endDate])->get();
    $news = News::all();

    $users = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

    $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
    $totalSales = $order->sum('cost');

    $salesData = [
        'labels' => $order->pluck('created_at')->map(fn($date) => $date->format('d M Y')),
        'data' => $order->pluck('cost')
    ];

    $chartData = [
        'labels' => $users->pluck('date'),
        'userRegistrations' => $users->pluck('total')
    ];

    $now = now();
    $lastMonth = $now->copy()->subMonth();
    $userLastMonth = User::whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])->count();
    $userThisMonth = User::whereBetween('created_at', [$now->startOfMonth(), $now->endOfMonth()])->count();

    $userGrowthPercentage = $userLastMonth > 0
        ? round((($userThisMonth - $userLastMonth) / $userLastMonth) * 100, 2)
        : ($userThisMonth > 0 ? 100 : 0);

    return view('admin.admin', compact(
        'user',
        'order',
        'news',
        'chartData',
        'totalSales',
        'salesData',
        'userGrowthPercentage',
        'totalUsers',
        'startDate',
        'endDate'
    ));
})->middleware(['auth', 'verified', 'is_admin'])->name('admin');


Route::get('/orderadmin', [OrderController::class, 'index'])
    ->middleware(['auth', 'verified','is_admin'])
    ->name('orderdash');

Route::resource('orders', OrderController::class)
    ->except(['index'])
    ->middleware(['auth', 'verified']);

    
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/user', function () {
        $users = User::where('role', 'h')->get();
        return view('user.user', compact('users'));
    })->name('user');

    Route::get('/user/{id}', function ($id) {
        $user = User::findOrFail($id);
        return view('user.userchoose', compact('user'));
    })->name('user.detail');

    Route::post('/user/order/{id}', function (Request $request, $id) {
        $validatedData = $request->validate([
            'waktu' => 'required|date',
            'batas' => 'required|date|after_or_equal:waktu',
            'cost' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
        ]);

        $pemesanId = Auth::id();

        if ($pemesanId == $id) {
            return back()->withErrors(['error' => 'Anda tidak bisa memesan diri sendiri.']);
        }

        $validatedData['pemesan_id'] = $pemesanId;
        $validatedData['pesan_id'] = $id;
        $validatedData['user_id'] = $id;
        $validatedData['approve'] = 'n';

        $order = Order::create($validatedData);

        // --- Generate PDF Invoice ---
        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $order,
            'pemesan' => Auth::user(),
            'pesan' => User::findOrFail($id),
        ]);

        $filename = 'invoice_' . $order->id . '.pdf';
        $pdfPath = storage_path('app/public/pdf/' . $filename);
        $pdf->save($pdfPath);

        return redirect()->route('user.detail', $id)->with('success', 'Order berhasil dikirim! Invoice tersedia di: ' . asset('storage/pdf/' . $filename));
    })->name('user.order');
});
// Apply 'is_admin' middleware for user control routes
Route::middleware(['auth', 'verified', 'is_admin'])->group(function () {
    Route::get('/usercontrol', [UserController::class, 'index'])->name('usercontrol.index');
    Route::get('/usercontrol/create', [UserController::class, 'create'])->name('usercontrol.create');
    Route::post('/usercontrol', [UserController::class, 'store'])->name('usercontrol.store');
    Route::get('/usercontrol/{user}/edit', [UserController::class, 'edit'])->name('usercontrol.edit');
    Route::post('/usercontrol/{user}', [UserController::class, 'update'])->name('usercontrol.update');
    Route::delete('/usercontrol/{user}', [UserController::class, 'destroy'])->name('usercontrol.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'showChat'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'storeMessage'])->name('chat.store');
    Route::get('/chat/search', [ChatController::class, 'searchUsers'])->name('chat.search');
    Route::get('/chat/messages/{userId}', [ChatController::class, 'fetchMessages'])->name('chat.messages');
});


Route::get('/host', function (Request $request) {
    $user = auth()->user();

    $from = $request->input('from') ?? now()->subMonth()->toDateString();
    $to = $request->input('to') ?? now()->toDateString();

    $orders = Order::with(['pemesan', 'pesan'])
        ->where('user_id', $user->id)
        ->whereBetween('created_at', [$from, $to])
        ->orderBy('pesan_id')
        ->get();

    $orderStats = Order::where('user_id', $user->id)
        ->whereBetween('created_at', [$from, $to])
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->groupBy('date')
        ->get();

    return view('host.host', compact('user', 'orders', 'orderStats', 'from', 'to'));
})->middleware('auth','verified');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




use App\Http\Controllers\NewsController;

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::post('/news', [NewsController::class, 'store'])->name('news.store');
Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
Route::post('/news/update/{id}', [NewsController::class, 'postUpdate'])->name('news.postUpdate');
Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

// Public side (read news by slug)
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');





require __DIR__.'/auth.php';


