<?php

declare(strict_types=1);

namespace Medpzl\Clubdata\Slug;

/**
 * Post modifier for program slugs to format them with Y-m-d date prefix
 */
class ProgramDateModifier
{
    /**
     * Modifies the slug to include date in Y-m-d format
     */
    public static function modify(array $parameters): string
    {
        $slug = $parameters['slug'];
        $record = $parameters['record'];

        // If datetime field is available, format the slug as Y-m-d-title
        if (!empty($record['datetime'])) {
            $datetime = new \DateTime($record['datetime']);
            $dateString = $datetime->format('Y-m-d');

            // Remove any existing date prefix or timestamp suffix from slug
            $cleanSlug = preg_replace('/^[0-9]{4}-[0-9]{2}-[0-9]{2}-/', '', $slug);
            $cleanSlug = preg_replace('/-[0-9]{4}-[0-9]{2}-[0-9]{2}t[0-9]{6}z$/', '', $cleanSlug);

            return $dateString . '/' . $cleanSlug;
        }

        return $slug;
    }
}
