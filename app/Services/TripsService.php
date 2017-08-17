<?php

namespace App\Services;

use App\Criteria\Trips\AllDriverTripsCriteria;
use App\Criteria\Trips\PastDriverTripsCriteria;
use App\Criteria\Trips\UpcomingDriverTripsCriteria;
use App\Models\Trip;
use App\Repositories\TripRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Services\Requests\UpdateTripRequest;
use App\Services\Requests\SearchTripRequest;
use App\Exceptions\User\UserHasNotPermissionsToDeleteTripException;
use App\Rules\DeleteTrip\TripOwnerRule;
use App\Services\Requests\CreateTripRequest;
use App\User;
use App\Validators\DeleteTripValidator;
use Prettus\Repository\Contracts\CriteriaInterface;

class TripsService
{
    private $tripRepository;
    private $deleteTripValidator;

    /**
     * TripsService constructor.
     * @param TripRepository $tripRepository
     * @param DeleteTripValidator $deleteTripValidator
     */
    public function __construct(TripRepository $tripRepository, DeleteTripValidator $deleteTripValidator)
    {
        $this->tripRepository = $tripRepository;
        $this->deleteTripValidator = $deleteTripValidator;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getAll(User $user)
    {
        return $this->tripRepository->getByCriteria(new AllDriverTripsCriteria($user));
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getUpcoming(User $user)
    {
        return $this->tripRepository->getByCriteria(new UpcomingDriverTripsCriteria($user));
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getPast(User $user)
    {
        return $this->tripRepository->getByCriteria(new PastDriverTripsCriteria($user));
    }

    /**
     * @param CreateTripRequest $request
     * @param $user
     * @return Trip
     */
    public function create(CreateTripRequest $request, $user): Trip
    {
        $tripAttributes = [
            'price' => $request->getPrice(),
            'seats' => $request->getSeats(),
            'start_at' => $request->getStartAt(),
            'end_at' => $request->getEndAt(),
            'vehicle_id' => $request->getVehicleId(),
            'user_id' => $user->id,
        ];

        $routeAttributes = [
            'from' => $request->getFrom(),
            'to' => $request->getTo(),
        ];

        $trip = $this->tripRepository->save(new Trip($tripAttributes));
        $trip->routes()->create($routeAttributes);

        return $trip;
    }

    /**
     * @param Trip $trip
     * @param UpdateTripRequest $request
     * @param $user
     * @return Trip
     */
    public function update(Trip $trip, UpdateTripRequest $request, $user): Trip
    {
        return $trip;
    }

    /**
     * @param Trip $trip
     * @param $user
     * @return Trip
     */
    public function delete(Trip $trip, $user)
    {
        $this->deleteTripValidator->validate($trip, $user);
        $this->tripRepository->softDelete($trip);

        return $trip;
    }

    /**
     * @param SearchTripRequest $request
     * @return array
     */
    public function search(SearchTripRequest $request) :array
    {
        $data = [];
        $faker = \Faker\Factory::create();
        $countAllData = $request->getLimit() * 4.2;
        for ($i = 1; $i < $countAllData + 1; $i++) {
            $user = factory(User::class)->make();
            $trip = factory(Trip::class)->make([
                'id' => $i,
                'start_at' => $faker->dateTimeInInterval('0 years', $interval = '+ 5 days')
            ]);

            $routes = [
                'from' => ['point' => $faker->city, 'id' => $i.'1'],
                'to' => ['point' => $faker->city, 'id' => $i.'2'],
                'points' => []
            ];
            $routes['points'][] = $routes['from'];
            $routes['points'][] = $routes['to'];
            if (rand(1, 1000) > 500) {
                array_unshift($routes['points'], ['point' => $faker->city, 'id' => $i.'3']);
            }
            if (rand(1, 1000) > 500) {
                $routes['points'][] = ['point' => $faker->city, 'id' => $i.'4'];
            }

            /** @var Carbon $start_at */
            $start_at = $trip->start_at;
            if ($start_at->isToday()) {
                $start = "Today";
            } else if ($start_at->isTomorrow()) {
                $start = "Tomorrow";
            } else {
                $start = [
                        'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
                    ][$start_at->dayOfWeek] . '. ' . str_pad($start_at->day, 2, '0', STR_PAD_LEFT) . ' ' . [
                        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ][$start_at->month - 1];
            }
            $hours = str_pad($start_at->hour, 2, '0', STR_PAD_LEFT);
            $minutes = str_pad($start_at->minute, 2, '0', STR_PAD_LEFT);
            $start .= " - {$hours}:{$minutes}";
            $data[] = [
                'id' => $i,
                'price' => $trip->price,
                'seats' => $trip->seats,
                'start_date' => $start,
                'start_at' => $trip->start_at->timestamp,
                'route' => $routes,
                'user' => [
                    'full_name' => $user->first_name . ' ' . $user->last_name,
                    'age' => Carbon::now()->year - $user->birth_date->year,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'birth_date' => $user->birth_date->timestamp,
                    'photo' => 'http://lorempixel.com/200/200/'
                ],
            ];
        }
        usort($data, function($a, $b) use ($request) {
            $first = $request->isAsc() ? 'a' : 'b';
            $second = $request->isAsc() ? 'b' : 'a';
            switch($request->getSort()) {
                case 'price':
                    return $$first['price'] <=> $$second['price'];
                case 'start_at':
                    return $$first['start_at'] <=> $$second['start_at'];
                default:
                    return $$first['id'] <=> $$second['id'];
            }
        });
        $pages = array_chunk($data, $request->getLimit());
        $countData = count($pages);
        return [
            'collection' => ($countData > $request->getPage()
                ? $pages[$request->getPage() - 1]
                : $pages[$countData - 1]),
            'meta' => [
                'total' => $countAllData,
                'price' => [ 'max' => 1000, 'min' => 0 ]
            ]
        ];
    }
}
