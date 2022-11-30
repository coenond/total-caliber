<?php

namespace App\Enums;

enum StravaSportTypeEnum
{
    case AlpineSki;
    case BackcountrySki;
    case Canoeing;
    case Crossfit;
    case EBikeRide;
    case Elliptical;
    case EMountainBikeRide;
    case Golf;
    case GravelRide;
    case Handcycle;
    case Hike;
    case IceSkate;
    case InlineSkate;
    case Kayaking;
    case Kitesurf;
    case MountainBikeRide;
    case NordicSki;
    case Ride;
    case RockClimbing;
    case RollerSki;
    case Rowing;
    case Run;
    case Sail;
    case Skateboard;
    case Snowboard;
    case Snowshoe;
    case Soccer;
    case StairStepper;
    case StandUpPaddling;
    case Surfing;
    case Swim;
    case TrailRun;
    case Velomobile;
    case VirtualRide;
    case VirtualRun;
    case Walk;
    case WeightTraining;
    case Wheelchair;
    case Windsurf;
    case Workout;
    case Yoga;

    public static function supportedForGoals(): array
    {
        return array_map(
            fn ($type) => $type->name,
            [StravaSportTypeEnum::Run, StravaSportTypeEnum::Ride, StravaSportTypeEnum::Swim]
        );
    }
}
