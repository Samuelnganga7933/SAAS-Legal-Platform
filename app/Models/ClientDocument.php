<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'case_id',
        'uploaded_by_user_id',
        'filename',
        'file_path',
        'file_type',
        'file_size',
        'is_confidential',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'is_confidential' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the case this document belongs to
     */
    public function case()
    {
        return $this->belongsTo(Case::class);
    }

    /**
     * Get the user who uploaded this document
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    /**
     * Get file size in human-readable format
     */
    public function getFormattedFileSize(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file extension
     */
    public function getExtension(): string
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * Check if file is a PDF
     */
    public function isPdf(): bool
    {
        return $this->file_type === 'pdf' || $this->getExtension() === 'pdf';
    }

    /**
     * Check if file is an image
     */
    public function isImage(): bool
    {
        return in_array($this->file_type, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    /**
     * Get download URL (to be implemented in controller)
     */
    public function getDownloadUrl(): string
    {
        return route('client.documents.download', $this->id);
    }

    /**
     * Get display name with formatting
     */
    public function getDisplayName(): string
    {
        return $this->filename . ' (' . $this->getFormattedFileSize() . ')';
    }
}
