<?php

namespace App\Traits;

/**
 * HasTranslations
 *
 * Automatically returns the current-locale column value when you access
 * a "translatable" attribute.  Falls back: current locale → English → raw.
 *
 * Usage in a model:
 *
 *   use HasTranslations;
 *   protected array $translatable = ['name', 'title', 'description'];
 *
 * Then $model->name  →  returns name_hi / name_en / name  based on locale.
 * The raw column is still accessible via $model->getRawOriginal('name').
 */
trait HasTranslations
{
    /**
     * Override Eloquent's getAttribute so translatable fields
     * automatically return the locale-specific column value.
     */
    public function getAttribute($key)
    {
        if ($this->isTranslatableAttribute($key)) {
            return $this->getTranslatedValue($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * Check whether the given key is in the $translatable array.
     */
    protected function isTranslatableAttribute(string $key): bool
    {
        return isset($this->translatable) && in_array($key, $this->translatable, true);
    }

    /**
     * Return the best available translation for the given field.
     * Priority: current locale → English → raw column.
     */
    protected function getTranslatedValue(string $key): mixed
    {
        $locale  = app()->getLocale();          // e.g. 'hi', 'gu', 'en'
        $localeCol = $key . '_' . $locale;       // e.g. 'name_hi'
        $enCol     = $key . '_en';               // e.g. 'name_en'

        // 1. Try current locale column
        if ($locale !== 'en' && !empty($this->attributes[$localeCol] ?? null)) {
            return $this->attributes[$localeCol];
        }

        // 2. Fall back to English column
        if (!empty($this->attributes[$enCol] ?? null)) {
            return $this->attributes[$enCol];
        }

        // 3. Fall back to raw column (legacy / base value)
        return $this->attributes[$key] ?? null;
    }

    /**
     * Convenience: get a specific locale's value directly.
     * e.g.  $model->getTranslation('name', 'gu')
     */
    public function getTranslation(string $key, string $locale): mixed
    {
        $col = $key . '_' . $locale;
        return $this->attributes[$col] ?? $this->attributes[$key] ?? null;
    }
}
