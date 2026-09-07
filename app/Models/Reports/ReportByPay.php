<?php

namespace App\Models\Reports;

use App\Models\Files\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $title
 * @property int $report_type_id
 * @property int $file_id
 * @property string $file_name
 * @property string $from_date
 * @property string $to_date
 * @property string $status
 *
 * @property-read File $file
 */
class ReportByPay extends Model
{
    protected $fillable = [
        'title',
        'report_type_id',
        'file_id',
        'file_name',
        'from_date',
        'to_date',
        'status'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getReportTypeId(): int
    {
        return $this->report_type_id;
    }

    public function setReportTypeId(int $report_type_id): void
    {
        $this->report_type_id = $report_type_id;
    }

    public function getFileId(): int
    {
        return $this->file_id;
    }

    public function setFileId(int $file_id): void
    {
        $this->file_id = $file_id;
    }

    public function getFileName(): string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): void
    {
        $this->file_name = $file_name;
    }

    public function getFromDate(): string
    {
        return $this->from_date;
    }

    public function setFromDate(string $from_date): void
    {
        $this->from_date = $from_date;
    }

    public function getToDate(): string
    {
        return $this->to_date;
    }

    public function setToDate(string $to_date): void
    {
        $this->to_date = $to_date;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
