<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorAction extends Model
{
    protected $fillable = ['visitor_id','action','url','details'];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // If visitor_id is not provided, attempt to resolve it from the current request IP
            if (empty($model->visitor_id)) {
                try {
                    $ip = request()->ip();
                    if ($ip) {
                        // Lazily create/find visitor record so visitor_id is never null
                        $visitor = Visitor::firstOrCreate(
                            ['ip' => $ip],
                            [
                                'user_agent' => request()->userAgent() ?? null,
                                'device' => null,
                                'platform' => null,
                                'browser' => null,
                            ]
                        );

                        if ($visitor && $visitor->id) {
                            $model->visitor_id = $visitor->id;
                        }
                    }
                } catch (\Throwable $e) {
                    // Don't throw here; allow the flow to continue so the app remains available.
                }
            }
        });
    }
}

