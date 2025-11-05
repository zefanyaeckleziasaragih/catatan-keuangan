<?php

namespace App\Livewire;

use App\Models\FinancialRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class FinanceLivewire extends Component
{
    use WithPagination, WithFileUploads;

    public $auth;
    public $search = '';
    public $filterType = '';
    public $filterCategory = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    // Add Record
    public $addType = 'expense';
    public $addCategory;
    public $addAmount;
    public $addDescription;
    public $addTransactionDate;
    public $addReceiptImage;

    // Edit Record
    public $editRecordId;
    public $editType;
    public $editCategory;
    public $editAmount;
    public $editDescription;
    public $editTransactionDate;
    public $editReceiptImage;
    public $currentReceiptImage;

    // Delete Record
    public $deleteRecordId;
    public $deleteRecordInfo;
    public $deleteConfirmAmount;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->auth = Auth::user();
        $this->addTransactionDate = date('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = FinancialRecord::where('user_id', $this->auth->id)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($this->search) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        if ($this->filterDateFrom) {
            $query->where('transaction_date', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo) {
            $query->where('transaction_date', '<=', $this->filterDateTo);
        }

        $records = $query->paginate(20);

        // Calculate totals
        $totalIncome = FinancialRecord::where('user_id', $this->auth->id)
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = FinancialRecord::where('user_id', $this->auth->id)
            ->where('type', 'expense')
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        return view('livewire.finance-livewire', [
            'records' => $records,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'incomeCategories' => $this->getIncomeCategories(),
            'expenseCategories' => $this->getExpenseCategories(),
        ]);
    }

    public function getIncomeCategories()
    {
        return ['Gaji', 'Bonus', 'Investasi', 'Freelance', 'Lainnya'];
    }

    public function getExpenseCategories()
    {
        return [
            'Makanan & Minuman',
            'Transportasi',
            'Belanja',
            'Tagihan',
            'Hiburan',
            'Kesehatan',
            'Pendidikan',
            'Lainnya'
        ];
    }

    public function addRecord()
    {
        $this->validate([
            'addType' => 'required|in:income,expense',
            'addCategory' => 'required|string|max:255',
            'addAmount' => 'required|numeric|min:0',
            'addDescription' => 'nullable|string',
            'addTransactionDate' => 'required|date',
            'addReceiptImage' => 'nullable|image|max:2048',
        ]);

        $receiptPath = null;
        if ($this->addReceiptImage) {
            $userId = $this->auth->id;
            $dateNumber = now()->format('YmdHis');
            $extension = $this->addReceiptImage->getClientOriginalExtension();
            $filename = $userId . '_' . $dateNumber . '.' . $extension;
            $receiptPath = $this->addReceiptImage->storeAs('receipts', $filename, 'public');
        }

        FinancialRecord::create([
            'user_id' => $this->auth->id,
            'type' => $this->addType,
            'category' => $this->addCategory,
            'amount' => $this->addAmount,
            'description' => $this->addDescription,
            'transaction_date' => $this->addTransactionDate,
            'receipt_image' => $receiptPath,
        ]);

        $this->reset([
            'addType',
            'addCategory',
            'addAmount',
            'addDescription',
            'addTransactionDate',
            'addReceiptImage'
        ]);

        $this->addType = 'expense';
        $this->addTransactionDate = date('Y-m-d');

        $this->dispatch('closeModal', id: 'addRecordModal');
        $this->dispatch('showAlert', [
            'type' => 'success',
            'message' => 'Data berhasil ditambahkan!'
        ]);
    }

    public function prepareEditRecord($id)
    {
        $record = FinancialRecord::where('id', $id)
            ->where('user_id', $this->auth->id)
            ->first();

        if (!$record) {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
            return;
        }

        $this->editRecordId = $record->id;
        $this->editType = $record->type;
        $this->editCategory = $record->category;
        $this->editAmount = $record->amount;
        $this->editDescription = $record->description;
        $this->editTransactionDate = $record->transaction_date->format('Y-m-d');
        $this->currentReceiptImage = $record->receipt_image;

        // Gunakan JavaScript dispatch untuk membuka modal
        $this->dispatch('showModal', id: 'editRecordModal');
    }

    public function editRecord()
    {
        $this->validate([
            'editType' => 'required|in:income,expense',
            'editCategory' => 'required|string|max:255',
            'editAmount' => 'required|numeric|min:0',
            'editDescription' => 'nullable|string',
            'editTransactionDate' => 'required|date',
            'editReceiptImage' => 'nullable|image|max:2048',
        ]);

        $record = FinancialRecord::where('id', $this->editRecordId)
            ->where('user_id', $this->auth->id)
            ->first();

        if (!$record) {
            $this->addError('editAmount', 'Data tidak ditemukan.');
            return;
        }

        // Handle new receipt image
        if ($this->editReceiptImage) {
            // Delete old image
            if ($record->receipt_image && Storage::disk('public')->exists($record->receipt_image)) {
                Storage::disk('public')->delete($record->receipt_image);
            }

            $userId = $this->auth->id;
            $dateNumber = now()->format('YmdHis');
            $extension = $this->editReceiptImage->getClientOriginalExtension();
            $filename = $userId . '_' . $dateNumber . '.' . $extension;
            $receiptPath = $this->editReceiptImage->storeAs('receipts', $filename, 'public');
            $record->receipt_image = $receiptPath;
        }

        $record->type = $this->editType;
        $record->category = $this->editCategory;
        $record->amount = $this->editAmount;
        $record->description = $this->editDescription;
        $record->transaction_date = $this->editTransactionDate;
        $record->save();

        $this->reset([
            'editRecordId',
            'editType',
            'editCategory',
            'editAmount',
            'editDescription',
            'editTransactionDate',
            'editReceiptImage',
            'currentReceiptImage'
        ]);

        $this->dispatch('closeModal', id: 'editRecordModal');
        $this->dispatch('showAlert', [
            'type' => 'success',
            'message' => 'Data berhasil diubah!'
        ]);
    }

    public function prepareDeleteRecord($id)
    {
        $record = FinancialRecord::where('id', $id)
            ->where('user_id', $this->auth->id)
            ->first();

        if (!$record) {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
            return;
        }

        $this->deleteRecordId = $record->id;
        $this->deleteRecordInfo = $record->category . ' - Rp ' . number_format($record->amount, 0, ',', '.');

        // Gunakan JavaScript dispatch untuk membuka modal
        $this->dispatch('showModal', id: 'deleteRecordModal');
    }

    public function deleteRecord()
    {
        $record = FinancialRecord::where('id', $this->deleteRecordId)
            ->where('user_id', $this->auth->id)
            ->first();

        if (!$record) {
            $this->addError('deleteConfirmAmount', 'Data tidak ditemukan.');
            return;
        }

        if ($this->deleteConfirmAmount != $record->amount) {
            $this->addError('deleteConfirmAmount', 'Jumlah konfirmasi tidak sesuai.');
            return;
        }

        // Delete receipt image if exists
        if ($record->receipt_image && Storage::disk('public')->exists($record->receipt_image)) {
            Storage::disk('public')->delete($record->receipt_image);
        }

        $record->delete();

        $this->reset(['deleteRecordId', 'deleteRecordInfo', 'deleteConfirmAmount']);

        $this->dispatch('closeModal', id: 'deleteRecordModal');
        $this->dispatch('showAlert', [
            'type' => 'success',
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterType', 'filterCategory', 'filterDateFrom', 'filterDateTo']);
        $this->resetPage();
    }
}