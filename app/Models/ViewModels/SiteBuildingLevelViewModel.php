<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\SiteMap;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingRoom;
use App\Models\ViewModels\SiteBuildingRoomViewModel;
use App\Models\ViewModels\QuestionnaireAnswerViewModel;

class SiteBuildingLevelViewModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_building_levels';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Append additiona info to the return data
     *
     * @var string
     */
    public $appends = [
        'building_name',
        'building_floor_name',
        'building_level_rooms',
    ];

    public function getBuildingLevelRoomDetails()
    {
        //return $this->hasMany('App\Models\SiteBuildingRoom', 'site_building_level_id', 'id');
        return SiteBuildingRoomViewModel::where('site_id', $this->site_id)
            ->where('site_building_id', $this->site_building_id)
            ->where('site_building_level_id', $this->id)
            ->get();
    }

    /****************************************
     *           ATTRIBUTES PARTS            *
     ****************************************/
    public function getBuildingNameAttribute()
    {
        return SiteBuilding::find($this->site_building_id)->name;
    }

    public function getBuildingFloorNameAttribute()
    {
        return SiteBuilding::find($this->site_building_id)->name . ' - ' . $this->name;
    }

    public function getBuildingLevelRoomsAttribute()
    {
        return $this->getBuildingLevelRoomDetails();
    }
}
