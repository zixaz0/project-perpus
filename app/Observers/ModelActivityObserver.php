<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ModelActivityObserver
{
    public function created($model)
    {
        $this->logActivity('membuat', $model);
    }

    public function updated($model)
    {
        $this->logActivity('mengubah', $model);
    }

    public function deleted($model)
    {
        $this->logActivity('menghapus', $model);
    }

    protected function logActivity($action, $model)
    {
        $user = Auth::user();
        $modelName = class_basename($model);

        $oldValues = $model->getOriginal();
        $newValues = $model->getChanges();

        $description = match ($action) {
            'membuat' => "Menambahkan {$modelName} baru: " . ($model->nama ?? $model->judul_buku ?? "ID #{$model->id}"),
            'mengubah' => $this->generateUpdateDescription($modelName, $oldValues, $newValues),
            'menghapus' => "Menghapus {$modelName}: " . ($model->nama ?? $model->judul_buku ?? "ID #{$model->id}"),
            default => '-',
        };

        ActivityLog::create([
            'user_id' => $user?->id,
            'activity' => strtoupper($action . ' ' . $modelName),
            'subject_type' => get_class($model),
            'subject_id' => $model->id ?? null,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
        ]);
    }

    protected function generateUpdateDescription($modelName, $oldValues, $newValues)
    {
        if (empty($newValues)) {
            return "Mengubah {$modelName} (tanpa perubahan signifikan)";
        }

        // Mapping label agar nama field tampil lebih natural
        $labels = [
            'stok' => 'Stok Buku',
            'harga' => 'Harga Buku',
            'judul_buku' => 'Judul Buku',
            'kode_buku' => 'Kode Buku',
            'kategori_id' => 'Kategori',
        ];

        $changes = [];
        foreach ($newValues as $key => $newValue) {
            if (in_array($key, ['updated_at', 'created_at'])) continue; // skip field timestamp

            $oldValue = $oldValues[$key] ?? '-';
            $label = $labels[$key] ?? ucfirst(str_replace('_', ' ', $key));

            $changes[] = "{$label} dari '{$oldValue}' menjadi '{$newValue}'";
        }

        return "Mengubah {$modelName} dengan perubahan: " . implode(', ', $changes);
    }
}