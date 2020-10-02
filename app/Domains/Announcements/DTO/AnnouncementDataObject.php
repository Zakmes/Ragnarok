<?php

namespace App\Domains\Announcements\DTO;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class AnnouncementDataObject
 *
 * @package App\Domains\Announcements\DTO
 */
class AnnouncementDataObject extends DataTransferObject
{
    public bool    $enabled;
    public string  $title;
    public string  $type;
    public string  $message;
    public ?string $area;
    public ?Carbon $starts_at;
    public ?Carbon $ends_at;

    /**
     * Method for mapping the seeder data to an data transfer object.
     *
     * @param  array $data The data array form the database seeder that needs to be mapped.
     * @return static
     */
    public static function fromSeeder(array $data): self
    {
        return new static([
            'title' => $data['title'],
            'area' => $data['area'],
            'type' => $data['type'],
            'message' => $data['message'],
            'enabled' => $data['enabled'],
        ]);
    }

    /**
     * Method for mapping the request data to an data transfer.
     *
     * @param  Request $request The request instance that will be used for the data mapping.
     * @return static
     */
    public static function fromRequest(Request $request): self
    {
        return new static([
            'enabled' => (bool) $request->get('status'),
            'title' => $request->get('title'),
            'area' => $request->get('area'),
            'type' => $request->get('type'),
            'message' => $request->get('message'),
            'starts_at' => now()->parse($request->get('start_date')),
            'ends_at' => now()->parse($request->get('end_date')),
        ]);
    }
}
