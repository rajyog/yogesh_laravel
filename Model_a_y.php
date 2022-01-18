<?php

namespace App\models\dbschools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Model_arr_year extends Model {

    protected $connection = "schools";
    protected $table = "arr_year";
    protected $year = null;
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }

    public function setYear($year) {
        $this->year = $year; // Set protected year value to passed year
        if ($year != null) {
            $this->table = $this->getTable() . '_' . $year; // Set table name to arr_year_$year
        }
    }

    public static function year($year) { // create instance for dynamic year value
        $instance = new static;
        $instance->setYear($year);
        return $instance->newQuery();
    }

    /**    ----------- Demo query to access table -----------

     *   public function demoQuery($table_year, $user_id) {
     *      $data = Model_arr_year::year($table_year)
     *             ->where('name_id', $user_id)
     *             ->where('field', 'year')
     *             ->first();
     *
     *    $result = FALSE;
     *    if ($data) {
     *        $result = $data->value;
     *    }
     *   return $result;
     * }
     *
     * 
     * @param type $year
     * @param type $id
     * @return type
     */
    public function getPupilYear($year, $id) {
        $data = Model_arr_year::year($year)
                ->where('name_id', $id)
                ->where('field', 'year')
                ->first();

        $result = FALSE;
        if ($data) {
            $result = $data->value;
        }
        return $result;
    }

    public function getArrData($sch_arr) {
//        $school_id = $sch_arr["school_id"];
        $field = $sch_arr["field"];
        $year = $sch_arr["year"];

        $getcampus = Model_arr_year::year($year)
                ->where('field', $field)
                ->where('value', '<>', '')
                ->groupBy('value')
                ->get()
                ->toArray();

        $result = FALSE;
        if (isset($getcampus) && !empty($getcampus)) {
            $result = $getcampus;
        }
        return $result;
    }

    public function arrYearData($fldVal) {
        $year = $fldVal['year'];
        $hybrid_option = "";
        if(isset($fldVal['hybrid_option']) && $fldVal['hybrid_option'] != ''){
            $hybrid_option = $fldVal['hybrid_option'];
        }
        
        $select_filter_querys = $fldVal['filter_querytogether'];
        $filter_optional_querytogether = "";
        if (isset($fldVal['filter_optional_querytogether']) && !empty($fldVal['filter_optional_querytogether'])) {
            $filter_optional_querytogether = $fldVal['filter_optional_querytogether'];
        }
        $yearData = Model_arr_year::year($year)->select('arr_year_' . $year . '.name_id', 'population.id', 'population.firstname', 'population.lastname', 'population.gender')
                ->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id');
        if (isset($fldVal['field']) && isset($fldVal['value'])) {
            $field = $fldVal['field'];
            $value = explode(',', $fldVal['value']);
            $yearData->where(function($q) use ($year, $select_filter_querys, $field, $value, $filter_optional_querytogether, $hybrid_option) {
                $q->orWhere(function($q) use ($year, $field, $value) {
                    $q->where('arr_year_' . $year . '.field', $field)
                            ->whereIn('arr_year_' . $year . '.value', $value);
                });
                if($hybrid_option != '' && $hybrid_option == 'and'){
                    foreach ($select_filter_querys as $key => $select_filter_query) {
                        $q->where(function($q) use ($year, $select_filter_query) {
                            $q->orWhere('arr_year_' . $year . '.field', $select_filter_query['field'])
                                    ->whereIn('arr_year_' . $year . '.value', $select_filter_query['value']);
                        });
                    }
                    if (isset($filter_optional_querytogether) && !empty($filter_optional_querytogether)) {
                        $q->where(function($q) use ($year, $filter_optional_querytogether) {
                            $q->orWhere('arr_year_' . $year . '.field', $filter_optional_querytogether['field'])
                                    ->where('arr_year_' . $year . '.value', '!=', '');
                        });
                    }
                } else{
                    foreach ($select_filter_querys as $key => $select_filter_query) {
                        $q->orWhere(function($q) use ($year, $select_filter_query) {
                            $q->orWhere('arr_year_' . $year . '.field', $select_filter_query['field'])
                                    ->whereIn('arr_year_' . $year . '.value', $select_filter_query['value']);
                        });
                    }
                    if (isset($filter_optional_querytogether) && !empty($filter_optional_querytogether)) {
                        $q->orWhere(function($q) use ($year, $filter_optional_querytogether) {
                            $q->orWhere('arr_year_' . $year . '.field', $filter_optional_querytogether['field'])
                                    ->where('arr_year_' . $year . '.value', '!=', '');
                        });
                    }
                }
            });
        } else {
            $yearData->where(function($q) use ($year, $select_filter_querys, $filter_optional_querytogether, $hybrid_option) {
                if($hybrid_option != '' && $hybrid_option == 'and'){
                    if (isset($filter_optional_querytogether) && !empty($filter_optional_querytogether)) {
                        $q->where(function($q) use ($year, $filter_optional_querytogether) {
                            $q->orWhere('arr_year_' . $year . '.field', $filter_optional_querytogether['field'])
                                    ->where('arr_year_' . $year . '.value', '!=', '');
                        });
                    }
                    foreach ($select_filter_querys as $key => $select_filter_query) {
                        $q->where(function($q) use ($year, $select_filter_query) {
                            $q->orWhere('arr_year_' . $year . '.field', $select_filter_query['field'])
                                    ->whereIn('arr_year_' . $year . '.value', $select_filter_query['value']);
                        });
                    }
                } else{
                    if (isset($filter_optional_querytogether) && !empty($filter_optional_querytogether)) {
                        $q->orWhere(function($q) use ($year, $filter_optional_querytogether) {
                            $q->orWhere('arr_year_' . $year . '.field', $filter_optional_querytogether['field'])
                                    ->where('arr_year_' . $year . '.value', '!=', '');
                        });
                    }
                    foreach ($select_filter_querys as $key => $select_filter_query) {
                        $q->orWhere(function($q) use ($year, $select_filter_query) {
                            $q->orWhere('arr_year_' . $year . '.field', $select_filter_query['field'])
                                    ->whereIn('arr_year_' . $year . '.value', $select_filter_query['value']);
                        });
                    }
                }
            });
        }
        if (isset($fldVal["level"]) && !empty($fldVal["level"])) {
            $level = $fldVal["level"];
            $yearData->where('population.level', $level);
        }
        if (isset($fldVal["lastname"]) && !empty($fldVal["lastname"])) {
            $testpupil = $fldVal["lastname"];
            $yearData->where('population.lastname', '!=', $testpupil);
        }
        if (isset($fldVal["gender"]) && !empty($fldVal["gender"])) {
            $sel_gender = $fldVal["gender"];
            $yearData->whereIn('population.gender', $sel_gender);
        }
        $yearData->groupBy('arr_year_' . $year . '.name_id');
        if (isset($fldVal['countquery']) && !empty($fldVal['countquery'])) {
            $countquery = 'COUNT(*) >= ' . $fldVal['countquery'];
            $yearData->havingRaw($countquery);
        }
        $yearData->orderBy('arr_year_' . $year . '.id', 'ASC');
        $yearData_results = $yearData->get();
        
        $result = FALSE;
        if ($yearData_results) {
            $result = $yearData_results;
        }
        return $result;
    }

    public function arrCmpsData($arrcmps) {
        $year = $arrcmps["year"];
        $data_field = $arrcmps["data_field"];
        $dataval = $arrcmps["dataval"];
        $campus = $arrcmps["campus"];
        $sel_gender = $arrcmps["gender"];
        $popid = $arrcmps["popid"];

        $yearData = Model_arr_year::year($year)
                ->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id')
                ->where(function($query) use ($year, $data_field, $dataval) {
                    $query->where('arr_year_' . $year . '.field', $data_field)
                    ->where('arr_year_' . $year . '.value', $dataval);
                })
                ->orWhere(function($query) use ($year, $campus) {
                    $query->where('arr_year_' . $year . '.field', 'campus')
                    ->whereIn('arr_year_' . $year . '.value', $campus);
                })
                ->where('population.id', $popid)
                ->whereIn('population.gender', $sel_gender)
                ->where('population.level', 1)
                ->where('population.lastname', '!=', 'testpupil')
                ->groupBy('arr_year_' . $year . '.name_id')
                ->havingRaw('COUNT(*) >= 2')
                ->get();

        $result = FALSE;
        if ($yearData) {
            $result = $yearData;
        }
        return $result;
    }

    public static function arrFinalData($arrcmps) {
        $year = $arrcmps["year"];
        $data_field = $arrcmps["data_field"];
        $dataval = $arrcmps["dataval"];
        $campus = $arrcmps["campus"];
        $sel_gender = $arrcmps["gender"];
        $genconpupil = $arrcmps["genconpupil"];

        if (isset($campus) && !empty($campus)) {
            $q1_pupils = Model_arr_year::year($year)
                    ->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id')
                    ->where(function($q) use ($year, $data_field, $dataval, $campus) {
                        $q->where(function($query) use ($year, $data_field, $dataval) {
                            $query->where('arr_year_' . $year . '.field', $data_field)
                            ->where('arr_year_' . $year . '.value', $dataval);
                        })->orWhere(function($query) use ($year, $campus) {
                            $query->where('arr_year_' . $year . '.field', 'campus')
                            ->whereIn('arr_year_' . $year . '.value', $campus);
                        });
                    })
                    ->whereIn('population.gender', $sel_gender)
                    ->where('population.level', 1)
                    ->where('population.lastname', '!=', 'testpupil')
                    ->whereIn('arr_year_' . $year . '.name_id', $genconpupil)
                    ->havingRaw('COUNT(*) >= 2')
                    ->groupBy('name_id')
                    ->select('arr_year_' . $year . '.name_id')
                    ->get();
        } else {
            $q1_pupils = Model_arr_year::year($year)
                    ->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id')
                    ->where(function($query) use ($year, $data_field, $dataval) {
                        $query->where('arr_year_' . $year . '.field', $data_field)
                        ->where('arr_year_' . $year . '.value', $dataval);
                    })
                    ->whereIn('population.gender', $sel_gender)
                    ->where('population.level', 1)
                    ->where('population.lastname', '!=', 'testpupil')
                    ->whereIn('arr_year_' . $year . '.name_id', $genconpupil)
                    ->groupBy('name_id')
                    ->select('arr_year_' . $year . '.name_id')
                    ->get();
        }

        $result = FALSE;
        if ($q1_pupils) {
            $result = $q1_pupils;
        }
        return $result;
    }

    public static function getFieldVal($fldVal) {
        $year = $fldVal['year'];
        $hybrid_option = "";
        if(isset($fldVal['hybrid_option']) && $fldVal['hybrid_option'] != ''){
            $hybrid_option = $fldVal['hybrid_option'];
        }
        $select_filter_querys = $fldVal['filter_querytogether'];
        $countquery = 'COUNT(*) >= ' . $fldVal['countquery'];
        $q1_pupils = Model_arr_year::year($year)
                ->select('name_id');
        
        if (isset($fldVal['field']) && isset($fldVal['value'])) {
            $field = $fldVal['field'];
            $value = $fldVal['value'];
            $q1_pupils->where(function($q) use ($field, $value, $select_filter_querys, $hybrid_option) {
                $q->where(function($q) use ($field, $value, $select_filter_querys) {
                    $q->where('field', $field)
                            ->where('value', $value);
                });
                if($hybrid_option != '' && $hybrid_option == 'and'){
                    foreach ($select_filter_querys as $key => $select_filter_query) {
                        $q->where(function($q) use ($select_filter_query) {
                            $q->orWhere('field', $select_filter_query['field'])
                                    ->whereIn('value', $select_filter_query['value']);
                        });
                    }
                } else{
                    foreach ($select_filter_querys as $key => $select_filter_query) {
                        $q->orWhere(function($q) use ($select_filter_query) {
                            $q->orWhere('field', $select_filter_query['field'])
                                    ->whereIn('value', $select_filter_query['value']);
                        });
                    }
                }
            });
        } else {
            $q1_pupils->where(function($q) use ($select_filter_querys, $hybrid_option) {
                if($hybrid_option != '' && $hybrid_option == 'and'){
                    foreach ($select_filter_querys as $key => $select_filter_query) {
                        $q->where(function($q) use ($select_filter_query) {
                            $q->orWhere('field', $select_filter_query['field'])
                                    ->whereIn('value', $select_filter_query['value']);
                        });
                    }
                } else{
                    foreach ($select_filter_querys as $key => $select_filter_query) {
                        $q->orWhere(function($q) use ($select_filter_query) {
                            $q->orWhere('field', $select_filter_query['field'])
                                    ->whereIn('value', $select_filter_query['value']);
                        });
                    }
                }
            });
        }
        
        if (isset($fldVal['genConPupil']) && !empty($fldVal['genConPupil'])) {
            $genConPupil = $fldVal['genConPupil'];
            $q1_pupils->whereIn('name_id', $genConPupil);
        }
        $q1_pupils->groupBy('name_id')
                ->havingRaw($countquery);
        $pupils_results = $q1_pupils->get();
        $result = FALSE;
        if ($pupils_results) {
            $result = $pupils_results;
        }
        return $result;
    }

    public function getPupilByYear($popYear) {
        $year = $popYear['year'];
        $field = $popYear['field'];
        $value = $popYear['value'];

        $pupils_year = Model_arr_year::year($year)->select('name_id')
                ->where(function($query) use ($field, $value) {
                    $query->where('field', $field)
                    ->whereIn('value', $value);
                })
                ->groupBy('name_id')
                ->orderBy('id', 'ASC')
                ->get();
        $result = FALSE;
        if ($pupils_year) {
            $result = $pupils_year;
        }
        return $result;
    }

    public function yearDataAll($conditions) {
        $year = $conditions['year'];
        $query = Model_arr_year::year($year);
        $query = $query->select('arr_year_' . $year . '.name_id', 'arr_year_' . $year . '.field', 'arr_year_' . $year . '.value', 'population.id', 'population.firstname', 'population.lastname', 'population.gender', 'population.dob');
        $query = $query->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id');
        $query = $query->where("population.level", 1)
                ->where("population.lastname", "!=", "testpupil");
        $query = $query->orderBy('arr_year_' . $year . '.id');
        $data = $query->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function totalPupilData($arr_data) {
        $year = $arr_data['year'];
        $field = $arr_data['field'];
        $value = $arr_data['value'];
        $pop_id = $arr_data['pop_id'];

        $data = Model_arr_year::year($year)->select('name_id')
                ->where(function ($query) use ($field, $value) {
                    $query->where('field', $field)
                    ->whereIn('value', $value);
                })
                ->where('name_id', $pop_id)
                ->groupBy('name_id')
                ->first();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function optionalFilter($year) {
        $data = Model_arr_year::year($year)
                ->whereNotIn('field', function ($query) {
                    $query->select('field')
                    ->from('arr_import_pupil');
                })
                ->where('value', '<>', '')
                ->groupBy('field')
                ->select('field')
                ->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function optionalFilterData($arr_data) {
        $year = $arr_data['year'];
        $custom_data = $arr_data['custom_data'];
        $sensitive = $arr_data['sensitive'];

        if ($custom_data != "") {
            $data = Model_arr_year::year($year)
                    ->whereNotIn('field', $custom_data)
                    ->whereNotIn('field', $sensitive)
                    ->where('value', '<>', '')
                    ->groupBy('field')
                    ->select('field')
                    ->get();
        } else {
            $data = Model_arr_year::year($year)
                    ->whereNotIn('field', $sensitive)
                    ->where('value', '<>', '')
                    ->groupBy('field')
                    ->select('field')
                    ->get();
        }

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function allOptioanlFilter($arr_data1) {
        $year = $arr_data1['year'];
        $field = $arr_data1['field'];
        $data = Model_arr_year::year($year)
                ->where('field', '!=', $field)
                ->where('value', '<>', '')
                ->groupBy('field')
                ->select('field')
                ->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getOptionalId($arr_data) {
        $year = $arr_data['year'];
        $value1 = $arr_data['value1'];
        $field = $arr_data['field'];
        $value = $arr_data['value'];

        if (isset($value) && !empty($value)) {
            $data = Model_arr_year::year($year)->select('arr_year_' . $year . '.name_id')
                    ->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id')
                    ->where('population.level', 1)
                    ->where('population.lastname', '!=', 'testpupil')
                    ->where(function ($query) use ($year, $value1) {
                        $query->where('arr_year_' . $year . '.field', 'year')
                        ->whereIn('arr_year_' . $year . '.value', $value1);
                    })->orwhere(function($q) use ($year, $field, $value) {
                        $q->where('arr_year_' . $year . '.field', $field)
                        ->where('arr_year_' . $year . '.value', $value);
                    })
                    ->groupBy('arr_year_' . $year . '.name_id')
                    ->havingRaw('COUNT(*) >= 1')
                    ->orderBy('arr_year_' . $year . '.id', 'ASC')
                    ->get();
        } else {
            $data = Model_arr_year::year($year)->select('arr_year_' . $year . '.name_id')
                    ->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id')
                    ->where('population.level', 1)
                    ->where('population.lastname', '!=', 'testpupil')
                    ->where(function ($query) use ($year, $value1) {
                        $query->where('arr_year_' . $year . '.field', 'year')
                        ->whereIn('arr_year_' . $year . '.value', $value1);
                    })
                    ->groupBy('arr_year_' . $year . '.name_id')
                    ->orderBy('arr_year_' . $year . '.id', 'ASC')
                    ->get();
        }
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function subSchoolData($arr_data) {
        $year = $arr_data['year'];
        $field = $arr_data['field'];
        $value = $arr_data['value'];
        $gender = $arr_data['gender'];

        $check_subschool_data = Model_arr_year::year($year)->select('arr_year_' . $year . '.name_id')
                ->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id')
                ->where(function($query) use ($year, $field, $value) {
                    $query->where('arr_year_' . $year . '.field', $field)
                    ->where('arr_year_' . $year . '.value', $value);
                })->where('population.gender', $gender)
                ->get();
        $result = "";
        if ($check_subschool_data) {
            $result = $check_subschool_data;
        }
        return $result;
    }

    public function checkPupilTotal($arr_data) {
        $year = $arr_data['year'];
        $field = $arr_data['field'];
        $value = $arr_data['value'];
        $type = $arr_data['type'];
        $school_db = $arr_data['school_db'];

        $dbVersion = DB::connection('schools')->table('arr_year_' . $year)->get();
        $dbVersion1 = DB::connection('schools')->table('ass_score_' . $year)->get();

        if (isset($dbVersion) && isset($dbVersion1)) {
            $check_pup = DB::table($school_db . '.arr_year_' . $year . ' as arr')
                    ->join($school_db . '.ass_score_' . $year . ' as ass', 'arr.name_id', '=', 'ass.pop_id')
                    ->where(function($query) use ($year, $field, $value) {
                        $query->where('arr.field', $field)
                        ->whereIn('arr.value', $value);
                    })->whereIn('ass.type', $type)
                    ->groupBy('ass.pop_id')
                    ->get(['ass.pop_id']);

//            $check_pup = $school_db . Model_arr_year::year($year)->select($school_db . '.ass_score_' . $year . '.pop_id')
//                            ->join($school_db . '.ass_score_' . $year, $school_db . '.arr_year_' . $year . '.name_id', '=', $school_db . 'ass_score_' . $year . '.pop_id')
//                            ->where(function($query) use ($school_db, $year, $field, $value) {
//                                $query->where($school_db . '.arr_year_' . $year . '.field', $field)
//                                ->whereIn($school_db . '.arr_year_' . $year . '.value', $value);
//                            })->whereIn($school_db . '.ass_score_' . $year . '.type', $type)
//                            ->groupBy($school_db . '.ass_score_' . $year . '.pop_id')
//                            ->get();
        }
        $result = "";
        if ($check_pup) {
            $result = $check_pup;
        }
        return $result;
    }

    public function pupilInDaySchl($arr_data) {
        $year = $arr_data['year'];
        $field = $arr_data['field'];
        $value = $arr_data['value'];
        $type = $arr_data['type'];
        $school_db = $arr_data['school_db'];

        $check_pup = DB::table($school_db . '.arr_year_' . $year . ' as arr')
                ->join($school_db . '.ass_score_' . $year . ' as ass', 'arr.name_id', '=', 'ass.pop_id')
                ->where(function($query) use ($year, $field, $value) {
                    $query->where('arr.field', $field)
                    ->whereIn('arr.value', $value);
                })->where('ass.type', $type)
                ->groupBy('ass.pop_id')
                ->get(['ass.pop_id']);

        $result = "";
        if ($check_pup) {
            $result = $check_pup;
        }
        return $result;
    }

    public function checkSubSchoolData($arr_data) {
        $year = $arr_data['year'];
        $field = $arr_data['field'];
        $value = $arr_data['value'];
        $field1 = $arr_data['field1'];
        $value1 = $arr_data['value1'];

        $check_subschool_data = Model_arr_year::year($year)
                ->where(function($query) use ($field, $value) {
                    $query->where('field', $field)
                    ->whereIn('value', $value);
                })
                ->orwhere(function($q) use ($field1, $value1) {
                    $q->where('field', $field1)
                    ->where('value', $value1);
                })
                ->groupBy('name_id')
                ->havingRaw('COUNT(*) >= 2')
                ->orderBy('id', 'ASC')
                ->select('name_id')
                ->get();
        $result = "";
        if ($check_subschool_data) {
            $result = $check_subschool_data;
        }
        return $result;
    }

    public function pupilDataWithCampus($dataArr) {
        $year = $dataArr['year'];
        $field = $dataArr['field'];
        $value = $dataArr['value'];
        $field1 = $dataArr['field1'];
        $value1 = $dataArr['value1'];
        $school_id = $dataArr['school_id'];

        $data = Model_arr_year::year($year)->select('arr_year_' . $year . '.name_id')
                ->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id')
                ->where(function($query) use ($year, $field, $value, $field1, $value1) {
                    $query->where(function($q) use ($year, $field, $value) {
                        $q->where('arr_year_' . $year . '.field', $field)
                        ->where('arr_year_' . $year . '.value', $value);
                    })->orwhere(function($qry) use ($year, $field1, $value1) {
                        $qry->where('arr_year_' . $year . '.field', $field1)
                        ->whereIn('arr_year_' . $year . '.value', $value1);
                    });
                })
                ->where('population.school_id', $school_id)
                ->groupBy('name_id')
                ->orderBy('population.id', 'ASC')
                ->havingRaw('COUNT(*) >= 2')
                ->get();

        $result = "";
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function pupilDataWithoutCampus($dataArr) {
        $year = $dataArr['year'];
        $field = $dataArr['field'];
        $value = $dataArr['value'];
        $school_id = $dataArr['school_id'];

        $data = Model_arr_year::year($year)->select('arr_year_' . $year . '.name_id')
                ->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id')
                ->where(function($query) use ($year, $field, $value) {
                    $query->where('arr_year_' . $year . '.field', $field)
                    ->where('arr_year_' . $year . '.value', $value);
                })
                ->where('population.school_id', $school_id)
                ->groupBy('name_id')
                ->orderBy('population.id', 'ASC')
                ->get();

        $result = "";
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getPupilDataByField($dataArr) {
        $year = $dataArr['year'];
        $field = $dataArr['field'];
        $value = $dataArr['value'];
        $field1 = $dataArr['field1'];
        $value1 = $dataArr['value1'];
        $data = Model_arr_year::year($year)->select('name_id')
                ->where(function($q) use ($field, $value, $field1, $value1) {
                    $q->where(function($qr) use ($field, $value) {
                        $qr->where('field', $field)
                        ->whereIn('value', $value);
                    })->orwhere(function($qr) use ($field1, $value1) {
                        $qr->where('field', $field1)
                        ->whereIn('value', $value1);
                    });
                })
                ->groupBy('name_id')
                ->havingRaw('COUNT(*) >= 2')
                ->orderBy('id', 'ASC')
                ->get();
        $result = "";
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getInfoByYear($academic_year, $pupil_id, $field) {
        if (isset($field['form_set']) && isset($field['form_teacher'])) {
            $pupil_details = Model_arr_year::year($academic_year)->where('name_id', $pupil_id)
                            ->where(function($q) {
                                $q->where('field', 'form_set')
                                ->orWhere('field', 'form_teacher');
                            })->first();
        } else {
            $pupil_details = Model_arr_year::year($academic_year)->where(['name_id' => $pupil_id, 'field' => $field])->first();
        }
        if (!empty($pupil_details)) {
            return $pupil_details['value'];
        }
        return null;
    }

    public function getValueWithAllField($dataArr) {

        $year = $dataArr['year'];
        $field = $dataArr['field'];
        $value = $dataArr['value'];
        $field1 = $dataArr['field1'];
        $value1 = $dataArr['value1'];
        $field2 = $dataArr['field2'];
        $value2 = $dataArr['value2'];

        $data = Model_arr_year::year($year)->select('name_id')
                ->where(function($q) use ($field, $value, $field1, $value1, $field2, $value2) {
                    $q->where(function($qr) use ($field, $value) {
                        $qr->where('field', $field)
                        ->whereIn('value', $value);
                    })->orwhere(function($qr) use($field1, $value1) {
                        $qr->where('field', $field1)
                        ->whereIn('value', $value1);
                    })->orwhere(function($qr) use ($field2, $value2) {
                        $qr->where('field', $field2)
                        ->whereIn('value', $value2);
                    });
                })
                ->groupBy('name_id')
                ->havingRaw('COUNT(*) >= 3')
                ->orderBy('id', 'ASC')
                ->get();

        $result = "";
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getPupilExtData($academicyear, $pop_id) {
        $pupil_details = Model_arr_year::year($academicyear)
                ->where('name_id', $pop_id)
                ->get()
                ->toArray();
        return $pupil_details;
    }

    public function storePupilData($academicyear, $newtitle, $newtitlevalue, $pop_id) {
        $save = new Model_arr_year;
        $save->setYear($academicyear);
        $save->name_id = $pop_id;
        $save->field = $newtitle;
        $save->value = $newtitlevalue;
        if ($save->save()) {
            $result['status'] = true;
        } else {
            $result['status'] = false;
        }

        return $result;
    }

    public function saveHousesAndYear($field, $value, $academicyear) {
        $save = new Model_arr_year;
        $save->setYear($academicyear);
        $save->name_id = '0';
        $save->field = $field;
        $save->value = $value;
        if ($save->save()) {
            $result['status'] = true;
        } else {
            $result['status'] = false;
        }

        return $result;
    }

//    public function deletePupil($id, $academicyear) {
//        $pupil_details = Model_arr_year::year($academicyear)
//                ->where('name_id', $id)
//                ->delete();
//        return TRUE;
//    }
    public function deletePupil($academicyear, $condition) {
        $pupil_details = Model_arr_year::year($academicyear)
                ->where($condition)
                ->delete();
        return $pupil_details;
    }

    public function get_data($academicyear, $id, $title) {
        $data = Model_arr_year::year($academicyear)
                ->where('name_id', $id)
                ->where('field', $title)
                ->first();
        return $data;
    }

    public function get_left_pupils($academicyear) {
        $data = Model_arr_year::year($academicyear)
                ->where('field', "left")
                ->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function updateData($academicyear, $id, $newtitle, $newtitlevalue) {
        $data_arr = array(
            'value' => $newtitlevalue
        );
        $data = Model_arr_year::year($academicyear)
                ->where('name_id', $id)
                ->where('field', $newtitle)
                ->update($data_arr);
        return TRUE;
    }

    public function getPupilHouse($year) {
        $data = Model_arr_year::year($year)
                ->select('id', 'value')
                ->where('name_id', '0')
                ->where('field', 'house')
                ->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getPupilYears($year) {

        $data = Model_arr_year::year($year)
                ->select('id', 'value')
                ->where('name_id', '0')
                ->where('field', 'year')
                ->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getYearName($dataArr) {
        $year = $dataArr['year'];
        $field = $dataArr['field'];

        $data = Model_arr_year::year($year)
                ->where('field', $field)
                ->where(function($q) {
                    $q->where('name_id', 0)
                    ->orWhere('name_id', '');
                })
                ->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getFieldName($dataArr) {
        $year = $dataArr['year'];
        $data = Model_arr_year::year($year)->select('field')
                ->distinct('field')
                ->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getFilterData($conditions) {
        $data = array();
        $query = Model_arr_year::year($conditions['year']);

        if (isset($conditions['select']) && isset($conditions['select'])) {
            $query = $query->select($conditions['select']);
        }

        if (isset($conditions['field']) && !empty($conditions['field'])) {
            $query = $query->where('field', $conditions['field']);
        }
        if (isset($conditions['name_id']) && !empty($conditions['name_id'])) {
            $query = $query->where('name_id', $conditions['name_id']);
        }
        if (isset($conditions['field_not']) && !empty($conditions['field_not'])) {
            $query = $query->where('field', '!=', $conditions['field_not']);
        }
        if (isset($conditions['value_not']) && !empty($conditions['value_not'])) {
            $query = $query->where('value', '<>', '');
        }
        if (isset($conditions['value_greater_than_blank']) && !empty($conditions['value_greater_than_blank'])) {
            $query = $query->where('value', '>', '');
        }

        if (isset($conditions['name_id_zero_blank']) && !empty($conditions['name_id_zero_blank'])) {
            $query = $query->where(function($q) {
                $q->where('name_id', 0)
                        ->orWhere('name_id', '');
            });
        }

        if (isset($conditions['name_id_not_zero_blank']) && !empty($conditions['name_id_not_zero_blank'])) {
            $query = $query->where(function($q) {
                $q->where('name_id', '!=', 0)
                        ->orWhere('name_id', '<>', '');
            });
        }
        if (isset($conditions['value_in']) && !empty($conditions['value_in'])) {
            $query = $query->whereIn('value', $conditions['value_in']);
        }

        if (isset($conditions['distinct']) && !empty($conditions['distinct'])) {
            $query = $query->distinct($conditions['select']);
        }

        if (!empty($conditions['field']) && !empty($conditions['field_not']) && !empty($conditions['value_not'])) {
            $data = $query->first();
        } else {
            $data = $query->get();
        }
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getFieldNameNotInImport($year) {
        $data = Model_arr_year::year($year)->select('field')
                ->distinct('field')
                ->whereNotIn('field', function($query) {
                    $query->select('field')->from('arr_import_pupil')->distinct('field');
                })
                ->where('value', '<>', '')
                ->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getFieldNameWithCustome($year, $where) {

        $data = Model_arr_year::year($year);
        $data->select('field');
        $data->distinct('field');
        if (isset($where['field_not_in']) && (!empty($where['field_not_in'])) && isset($where['field_not_in_lavel_4']) && (!empty($where['field_not_in_lavel_4']))) {
            $data->whereNotIn('field', $where['field_not_in']);
            $data->whereNotIn('field', $where['field_not_in_lavel_4']);
            $data->where('value', '<>', '');
        }

        if ((isset($where['level_not_in_4'])) && (!empty($where['level_not_in_4']))) {
            $data->where('value', '!=', 'year');
            $data->where('value', '<>', '');
        }
//        $daa = $data->toSql();
        $data = $data->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getAllPupilYear($year) {
        $data = Model_arr_year::year($year)
                ->select('id', 'value')
                ->where('field', 'year')
                ->groupby('value')
                ->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getFiledWithoutYear($year) {
        $data = Model_arr_year::year($year);
        $data->select('field');
        $data->distinct('field');
        $data->where('field', '!=', 'year');
        $data = $data->get();
        $data = $data->toArray();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getFiledValue($year, $field) {
        $data = Model_arr_year::year($year);
        $data->select('value');
        $data->distinct('value');
        $data->where('field', '=', $field);
        $data->where('value', '<>', '');
        $data = $data->get();
        $data = $data->toArray();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getAllPupilHouse($year) {
        $data = Model_arr_year::year($year)
                ->select('id', 'value')
                ->where('field', 'house')
                ->groupby('value')
                ->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getPriorityPupilWithYear($param) {
        $year = $param['year'];
        $field = $param['field'];
        $value = $param['value'];
        $school_id = $param['school_id'];
        $level = $param['level'];
        $lastname = $param['lastname'];

        $data = Model_arr_year::year($year)->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id');
        $data->select('name_id', 'firstname', 'lastname');
        $data->where(function($q) use ($field, $value) {
            $q->where('field', $field)
                    ->whereIn('value', $value);
        });
        $data->where('school_id', $school_id);
        $data->where('level', $level);
        $data->where('lastname', '!=', $lastname);
        $data->groupby('name_id');
        $data->orderBy('name_id', 'ASC');
        $data->distinct();
        $data = $data->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getFiledOnlyYear($year) {
        $data = Model_arr_year::year($year);
        $data->select('value');
        $data->distinct('value');
        $data->where('field', '=', 'year');
        $data = $data->get();
        $data = $data->toArray();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getFieldvalue($conditions) {
        $year = $conditions["year"];
        $field = $conditions["field"];
        $getFieldvalue = Model_arr_year::year($year)
                ->where('field', $field)
                ->where('value', '<>', '')
                ->first();
        $result = FALSE;
        if (isset($getFieldvalue) && !empty($getFieldvalue)) {
            $result = $getFieldvalue;
        }
        return $result;
    }

    public function getHouseYearCampusAllData($param) {
        $year = $param['year'];
        $name_id = $param['name_id'];
        $field = $param['field'];

        $data = Model_arr_year::year($year)
                ->select('value', 'field', 'name_id')
                ->where('name_id', $name_id)
                ->whereIn('field', $field)
                ->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getYear($year, $conditions, $together_queries = array()) {

        $value = $conditions['value'];
        $field = $conditions['field'];
//        $value_house = $conditions['value_house'];
        $data = Model_arr_year::year($year)
                ->select('name_id', 'value')
                ->distinct('name_id', 'value')
                ->where(function($query) use ($field, $value) {
            $query->where('field', $field)
            ->whereIn('value', $value);
        });
        if (isset($together_queries) && !empty($together_queries)) {
            foreach ($together_queries as $together_query) {
                $data->orWhere(function ($query) use ($together_query) {
                    $query->where('field', $together_query['title_filter'])
                            ->whereIn('value', $together_query['optional_values']);
                });
            }
        }
        $data->groupBy('name_id');
        if (isset($conditions['count_query']) && !empty($conditions['count_query'])) {
            $count_query = 'COUNT(*) = ' . $conditions['count_query'];
            $data->havingRaw($count_query);
        }
        $data->orderBy('id', 'ASC');
        $data = $data->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getPupilWithFilter($conditions) {
        $year = $conditions['year'];
        $field = $conditions['field'];
        $selected_year = $conditions['selected_year'];
        $together_query = $conditions['together_query'];
        $count_query = $conditions['count_query'];
        $gender = $conditions['gender'];
        $data = Model_arr_year::year($year);
        $data->select('arr_year_' . $year . '.name_id', 'arr_year_' . $year . '.value',
                DB::raw("(SELECT value FROM arr_year_$year WHERE field = 'name_code' AND arr_year_$year.name_id = population.id) as name_code"),
                'population.id', 'population.mis_id', 'population.firstname', 'population.lastname', 'population.gender', 'population.dob', 'population.username', 'population.password');
        $data->join('population', 'population.id', '=', 'arr_year_' . $year . '.name_id');
        $data->distinct('arr_year_' . $year . '.name_id', 'arr_year_' . $year . '.value');
        if (isset($gender) && !empty($gender)) {

            $str = "'" . implode("','", $gender) . "'";
            if (count($gender) > 1) {
                $data->whereRaw("LOWER(population.gender) IN($str)");
            } else {
                $data->whereRaw("LOWER(population.gender) = $str");
            }
        }
        $data->where(function($query) use ($year, $field, $selected_year, $together_query, $conditions) {
            if (isset($selected_year) && !empty($selected_year)) {
                $query->where(function($query1) use ($year, $field, $selected_year) {
                    $query1->where('arr_year_' . $year . '.field', $field);
                    $query1->whereIn('arr_year_' . $year . '.value', $selected_year);
                });
            }
            if (isset($conditions['option']) && $conditions['option'] == 'and') {
                if (isset($together_query) && !empty($together_query)) {
                    foreach ($together_query as $together) {
                        $query->where(function ($query2) use ($year, $together) {
                            $query2->where('arr_year_' . $year . '.field', $together['title_filter'])
                                    ->whereIn('arr_year_' . $year . '.value', $together['optional_values']);
                        });
                    }
                }
            } else {
                if (isset($together_query) && !empty($together_query)) {
                    foreach ($together_query as $together) {
                        $query->orWhere(function ($query2) use ($year, $together) {
                            $query2->where('arr_year_' . $year . '.field', $together['title_filter'])
                                    ->whereIn('arr_year_' . $year . '.value', $together['optional_values']);
                        });
                    }
                }
            }
        });
        $data->where('population.lastname', '!=', "testpupil");
        $data->where('population.mis_id', '!=', "");
//        $data->where('arr_year_' . $year . '.field', '!=', 'left');
        $data->groupBy('arr_year_' . $year . '.name_id');
        if (isset($count_query) && !empty($count_query)) {
            $count_query = 'COUNT(*) >= ' . $count_query;
            $data->havingRaw($count_query);
        }
        $data->orderBy('arr_year_' . $year . '.id', 'ASC');
        $data = $data->get();
        
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getCurrentYearPupil($year) {
        $data = Model_arr_year::year($year);
        $data->select('arr_year_' . $year . '.name_id', 'arr_year_' . $year . '.value', 'population.id', 'population.firstname', 'population.lastname', 'population.gender', 'population.dob');
        $data->join('population', 'population.id', '=', 'arr_year_' . $year . '.name_id');
        $data->distinct('arr_year_' . $year . '.name_id', 'arr_year_' . $year . '.value');
        $data->groupBy('arr_year_' . $year . '.name_id');
        $data = $data->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getHouseData($acyear) {

        $gethousedata = Model_arr_year::year($acyear)
                ->select('value')
                ->distinct()
                ->where('field', 'house')
                ->where('value', '!=', '')
                ->get();

        $result = FALSE;
        if ($gethousedata) {
            $result = $gethousedata;
        }
        return $result;
    }

    public function getCampusData($acyear) {
        $getcampusdata = Model_arr_year::year($acyear)
                ->select('value')
                ->distinct()
                ->where('field', 'campus')
                ->where('value', '!=', '')
                ->get();

        $result = FALSE;
        if ($getcampusdata) {
            $result = $getcampusdata;
        }
        return $result;
    }

    public function getcampusdatal5($optionalcampus, $year) {
        $getcampusdata = Model_arr_year::year($year)
                ->select('name_id')
                ->distinct()
                ->whereIn('value', $optionalcampus)
                ->get();

        $result = FALSE;
        if (isset($getcampusdata) && !empty($getcampusdata)) {
            $result = $getcampusdata;
        }

        return $result;
    }

    public function gethousedatal5($cam_data, $year) {
        $gethousedatal5 = Model_arr_year::year($year)
                ->select('value')
                ->distinct()
                ->where('field', 'house')
                ->whereIn('name_id', $cam_data)
                ->get();

        $result = FALSE;
        if ($gethousedatal5) {
            $result = $gethousedatal5;
        }
        return $result;
    }

    public function cutomdata($year, $custom) {
        $cutomdata = Model_arr_year::year($year)
                ->select('field')
                ->distinct()
                ->whereNotIn('field', $custom)
                ->get();

        $result = FALSE;
        if ($cutomdata) {
            $result = $cutomdata;
        }
        return $result;
    }

    public function none_cutomdata($year) {
        $none_cutomdata = Model_arr_year::year($year)
                ->select('field')
                ->distinct()
                ->where('field', '!=', 'year')
                ->get();

        $result = FALSE;
        if ($none_cutomdata) {
            $result = $none_cutomdata;
        }
        return $result;
    }

    public function getImportPupil($year, $subject_arr) {
        $getimportpupil = Model_arr_year::year($year)
                ->select('field')
                ->distinct('field')
                ->where('value', '>=', '')
                ->where('field', '!=', 'year')
                ->where('field', 'not like', $subject_arr)
                ->get();
        return $getimportpupil;
    }

    public function getAllData($year) {
        $getAll = Model_arr_year::year($year)
                ->select('*')
                ->get();
        $result = FALSE;
        if ($getAll) {
            $result = $getAll;
        }
        return $result;
    }

    public function getNewPupilImport($academicyear, $user_id) {
        $data = Model_arr_year::year($academicyear)
                ->select('*')
                ->whereIn('name_id', $user_id)
                ->where('field', '=', 'year')
                ->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function checkArrYear($academicyear, $pop_id) {
        $pupil_details = Model_arr_year::year($academicyear)
                ->where('name_id', $pop_id)
                ->where('field', 'year')
                ->get();
        return $pupil_details;
    }

    public function getArrIdField($acyear, $name_id, $field) {
        $getarridfield = Model_arr_year::year($acyear)
                ->select('*')
                ->where('name_id', $name_id)
                ->where('field', '=', $field)
                ->get();

        $result = FALSE;
        if ($getarridfield) {
            $result = $getarridfield;
        }
        return $result;
    }

    public function updatenameidfield($acyear, $name_id, $field, $value) {
        $updateArr = (['name_id' => $name_id, 'field' => $field, 'value' => $value]);
        $updatenameidfield = Model_arr_year::year($acyear)
                ->where('name_id', $name_id)
                ->where('field', $field)
                ->update($updateArr);
        return TRUE;
    }

    public function storeStaffData($academicyear, $newtitle, $newtitlevalue, $pop_id) {
        $save = new Model_arr_year;
        $save->setYear($academicyear);
        $save->name_id = $pop_id;
        $save->field = $newtitle;
        $save->value = $newtitlevalue;
        if ($save->save()) {
            $result['status'] = true;
        } else {
            $result['status'] = false;
        }
    }

    public function getHouseByYear($cyear) {
        $result = FALSE;
        $gethouseQuery = Model_arr_year::year($cyear)
                        ->select('value')
                        ->distinct()
                        ->where('field', 'house')
                        ->where('value', '!=', '')->get();
        $gethousedata = $gethouseQuery;

        if ($gethousedata) {
            $result = $gethousedata;
        }
//        }
        return $result;
    }

    public function getUntrackedYear($condition) {
        $year = $condition['year'];
        $field = $condition['field'];
        $name_id_zero = $condition['name_id_zero'];

        $data = Model_arr_year::year($year);
        $data->select('value');
        $data->distinct('value');
        $data->where('field', $field);
        $data->Where(function ($query) use ($name_id_zero) {
            $query->where('name_id', $name_id_zero)
                    ->orwhere('name_id', '');
        });
        $data->orderBy('value', 'ASC');
        $data = $data->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getAllowdCampus($condition) {

        $year = $condition['year'];
        $field = $condition['field'];
        $value_zero = $condition['value_zero'];
        $value_blank = $condition['value_blank'];

        $data = Model_arr_year::year($year);
        $data->select('value');
        $data->distinct('value');
        $data->where('field', $field);
        $data->where('value', '<>', "$value_zero");
        $data->where('value', '<>', $value_blank);
        $data->orderBy('value', 'ASC');
        $data = $data->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getCampusUser($condition) {
        $year = $condition['year'];
        $field = $condition['field'];
        $value = $condition['value'];

        $data = Model_arr_year::year($year);
        $data->select('name_id');
        $data->distinct('name_id');
        $data->where('field', $field);
        $data->where('value', $value);
        $data->orderBy('name_id', 'ASC');
        $data = $data->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getHousesByCampuses($condition) {
        $year = $condition['year'];
        $field = $condition['field'];
        $name_id = $condition['name_id'];

        $data = Model_arr_year::year($year);
        $data->select('value');
        $data->distinct('value');
        $data->where('field', $field);
        $data->whereIn('name_id', $name_id);
        $data->orderBy('value', 'ASC');
        $data = $data->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getAllowdHouse($condition) {

        $year = $condition['year'];
        $field = $condition['field'];
        $value_zero = $condition['value_zero'];
        $value_blank = $condition['value_blank'];
        $data = Model_arr_year::year($year);
        $data->select('value');
        $data->distinct('value');
        $data->where('field', $field);
        $data->where('value', '<>', "$value_zero");
        $data->where('value', '<>', $value_blank);
        $data->orderBy('value', 'ASC');
        $data = $data->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getAllField($condition) {

        $year = $condition['year'];
        $sensitive = (isset($condition['field']) && !empty($condition['field']) ? $condition['field'] : '');
        $data = Model_arr_year::year($year);
        $data->select('field');
        if (isset($sensitive) && !empty($sensitive)) {
            $data->whereNotIn('field', $sensitive);
        }
        if (isset($condition['check_custom_field']) && !empty($condition['check_custom_field'])) {
            $data->where('field', 'not like', $condition['check_custom_field'].'%');
        }
        $data->distinct('field');
        $data->orderBy('field', 'ASC');
        $data = $data->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getDataByNameId($year, $nameid_array) {
        $data = Model_arr_year::year($year)
                ->whereIn('name_id', $nameid_array)
                ->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getAllValue($condition) {
        $year = $condition['year'];
        $field = $condition['field'];
        $value_zero = $condition['value_zero'];
        $value_blank = $condition['value_blank'];


        $data = Model_arr_year::year($year);
        $data->select('value');
        $data->distinct('value');
        $data->where('field', $field);
        $data->where('value', '<>', "$value_zero");
        $data->where('value', '<>', $value_blank);
        $data->orderBy('value', 'ASC');
        $data = $data->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getPupilDataByYear($year, $conditions) {
        $pupils_year = Model_arr_year::year($year)->select('name_id')
                ->distinct('name_id')
                ->where($conditions)
                ->orderBy('name_id', 'ASC')
                ->get();
        $result = FALSE;
        if ($pupils_year) {
            $result = $pupils_year;
        }
        return $result;
    }

    public function getPupilYearGroup($condition) {
        $year = $condition['year'];
        $field = $condition['field'];
        $value = $condition['value'];

        $data = Model_arr_year::year($year);
        $data->select('name_id', 'value');
        $data->distinct('name_id');
        $data->distinct('value');
        $data->where('field', $field);
        $data->whereIn('value', $value);
        $data->orderBy('value', 'ASC');
        $data = $data->get();

        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getAcpOverviewData($acyear, $selectedyear, $selectedmonth, $selected_gender, $optionalfilter, $query_count, $hybrid_option = "") {
        $acpdata = Model_arr_year::year($acyear);
        $acpdata->join('population', 'population.id', '=', 'arr_year_' . $acyear . '.name_id');
        $acpdata->select('name_id', 'value');
        $acpdata->distinct('name_id');
        if (isset($selected_gender) && !empty($selected_gender)) {
            $str = "'" . implode("','", $selected_gender) . "'";
            if (count($selected_gender) > 1) {
                $acpdata->whereRaw("LOWER(population.gender) IN($str)");
            } else {
                $acpdata->whereRaw("LOWER(population.gender) = $str");
            }
        }
        if($hybrid_option == 'and'){
            $acpdata->where('field', '=', 'year');
            $acpdata->where(function($q) use($selectedyear) {
                $q->whereIn('value', $selectedyear);
            });

            $acpdata->where('field', '=', 'month');
            $acpdata->where(function($q) use($selectedmonth) {
                $q->whereIn('value', $selectedmonth);
            });

            if (isset($optionalfilter) && !empty($optionalfilter)) {
                foreach ($optionalfilter as $optionalkey => $optionaldata) {
                    $acpdata->where('field', '=', $optionalkey);
                    $acpdata->where(function($q) use($optionaldata) {
                        $q->whereIn('value', $optionaldata);
                    });
                }
            }
        } else{
            $acpdata->where('field', '=', 'year');
            $acpdata->where(function($q) use($selectedyear) {
                $q->whereIn('value', $selectedyear);
            });

            $acpdata->orWhere('field', '=', 'month');
            $acpdata->where(function($q) use($selectedmonth) {
                $q->whereIn('value', $selectedmonth);
            });

            if (isset($optionalfilter) && !empty($optionalfilter)) {
                foreach ($optionalfilter as $optionalkey => $optionaldata) {
                    $acpdata->orWhere('field', '=', $optionalkey);
                    $acpdata->where(function($q) use($optionaldata) {
                        $q->whereIn('value', $optionaldata);
                    });
                }
            }
        }

        $acpdata->groupBy('name_id');
        $cntquery = 'COUNT(*) <= ' . $query_count;
        $acpdata->havingRaw($cntquery);
        $acpdata->orderBy('arr_year_' . $acyear . '.id', 'ASC');
        $getacpdata = $acpdata->get();
        $result = FALSE;
        if ($getacpdata) {
            $result = $getacpdata;
        }
        return $result;
    }

//    public function getCampusAndHouse($year) {
//        $data = Model_arr_year::year($year)
//                ->where('field', 'house')
//                ->orWhere('field', 'campus')
//                ->count();
//
//        return $data;
//    }

    public function getYearDataOfPupil($myid, $acyear) {
        $getdata = Model_arr_year::year($acyear)
                ->select('*')
                ->where('name_id', $myid)
                ->where('field', 'year')
                ->first();


        $result = FALSE;
        if ($getdata) {
            $result = $getdata;
        }
        return $result;
    }

    public function getNameId($year, $query_years) {
        $field = $query_years['field'];
        $value = $query_years['value'];

        $query = Model_arr_year::year($year)
                        ->select('name_id')
                        ->where(function($q) use ($field, $value) {
                            $q->where('field', '=', $field)
                            ->whereIn('value', $value);
                        })
                        ->orderBy('name_id', 'ASC')
                        ->distinct()->get();
        return $query;
    }

    public function getNameIdCount($conditions) {
        $year = $conditions['year'];
        $query = Model_arr_year::year($year);
        $query->select('arr_year_' . $year . '.name_id');
        $query->distinct('arr_year_' . $year . '.name_id');
        $query->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id');
        $query->where("population.level", 1)
                ->where("population.lastname", "!=", "testpupil");
        $data = $query->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getTerm($conditions) {
        $year = $conditions['year'];
        $start_end_date = $conditions['start_end_date'];
        $query = Model_arr_year::year($year);
        $query->select('arr_year_' . $year . '.name_id');
        $query->distinct('arr_year_' . $year . '.name_id');
        $query->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id');
        $query->where("population.level", 1)
                ->where("population.lastname", "!=", "testpupil")
                ->whereIn('arr_year_' . $year . '.name_id', function ($q) use ($year, $start_end_date) {
                    $q->select('ass_score_' . $year . '.pop_id')->from('ass_score_' . $year)
                    ->where('ass_score_' . $year . '.type', "at")
                    ->where(function($q) use ($start_end_date) {
                        $q->whereBetween(DB::raw('SUBSTRING(datetime,1,6)'), $start_end_date);
                    });
                })
                ->where(function($q) use ($year, $start_end_date) {
                    $q->whereIn('arr_year_' . $year . '.name_id', function ($q) use ($year, $start_end_date) {
                        $q->select('ass_score_' . $year . '.pop_id')->from('ass_score_' . $year)
                        ->where('ass_score_' . $year . '.type', "sch")
                        ->where(function($q) use ($start_end_date) {
                            $q->whereBetween(DB::raw('SUBSTRING(datetime,1,6)'), $start_end_date);
                        });
                    })
                    ->orWhere(function($q) use ($year, $start_end_date) {
                        $q->whereIn('arr_year_' . $year . '.name_id', function ($q) use ($year, $start_end_date) {
                            $q->select('ass_score_' . $year . '.pop_id')->from('ass_score_' . $year)
                            ->where('ass_score_' . $year . '.type', "hs")
                            ->where(function($q) use ($start_end_date) {
                                $q->whereBetween(DB::raw('SUBSTRING(datetime,1,6)'), $start_end_date);
                            });
                        });
                    });
                });
        $data = $query->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getTermLevel($conditions) {
        $year = $conditions['year'];
        $start_end_date = $conditions['start_end_date'];
        $school_id = $conditions['school_id'];
        $type = $conditions['type'];
        $query = Model_arr_year::year($year);
        $query->select('arr_year_' . $year . '.name_id');
        $query->distinct('arr_year_' . $year . '.name_id');
        $query->leftjoin('population', 'arr_year_' . $year . '.name_id', '=', 'population.id');
        $query->leftjoin('ass_score_' . $year, 'ass_score_' . $year . '.pop_id', '=', 'population.id');
        $query->where("population.level", 1)
                ->whereBetween(DB::raw('SUBSTRING(ass_score_' . $year . '.datetime,1,6)'), $start_end_date)
                ->where("population.school_id", $school_id);
        if ($type == 'hs') {
            $type_array =  array('hs','sch');
            $query->whereIn('ass_score_' . $year . '.type', $type_array);
        } else {
            $query->where('ass_score_' . $year . '.type', 'at');
        }
        $query->where("population.lastname", "!=", 'testpupil');
        $data = $query->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getSubSchool($conditions) {
        $year = $conditions['year'];
        $field = $conditions['field'];
        $value = $conditions['value'];
        $pid_term1 = $conditions['name_id'];
        $query = Model_arr_year::year($year)
                ->select('name_id')
                ->where('field', $field)
                ->whereIn('value', $value)
                ->whereIn('name_id', $pid_term1)
                ->distinct();
        $data = $query->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getCampusHouse($year) {
        $data = Model_arr_year::year($year)->select('field')
                ->distinct('field')
                ->where('field', 'house')
                ->orWhere('field', 'campus')
                ->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function arrYearPupildata($academicyear, $pop_id) {
        $pupil_details = Model_arr_year::year($academicyear)
                ->select('value')
                ->where('name_id', $pop_id)
                ->where('field', 'year')
                ->first();
        return $pupil_details;
    }

    public function getPupilDataByYearWithGender($year, $conditions) {
        $field = $conditions['field'];
        $value = $conditions['value'];
        $gender = $conditions['gender'];

        $query = Model_arr_year::year($year);
        $query = $query->select('arr_year_' . $year . '.name_id', 'population.gender');
        $query = $query->join('population', 'arr_year_' . $year . '.name_id', '=', 'population.id');
        $query = $query->distinct('arr_year_' . $year . '.name_id');
        $query = $query->where('arr_year_' . $year . '.field', $field);
        $query = $query->where('arr_year_' . $year . '.value', $value);
        $query = $query->where('population.gender', $gender);
        $query = $query->where("population.lastname", "!=", "testpupil");
        $query = $query->orderBy('arr_year_' . $year . '.name_id');
        $query = $query->get();
        $result = FALSE;
        if ($query) {
            $result = $query;
        }
        return $result;
    }

    public function yearCampusData($year, $conditions) {
        $query = Model_arr_year::year($year)->select('name_id', 'field', 'value')
                ->distinct('name_id')
                ->whereIn('name_id', $conditions['name_id'])
                ->where('field', 'campus')
                ->where('value', '!=', '<>');
        $query->orderBy('name_id', 'ASC');
        $data = $query->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getYearValue($conditions) {
        $year = $conditions['year'];
        $field = $conditions['field'];
        $data = Model_arr_year::year($year)
                ->select('name_id', 'field', 'value')
                ->distinct('name_id', 'field', 'value')
                ->where(function($query) use ($field) {
            $query->where('field', $field)
            ->where('value', '!=', '');
        });
        $data->groupBy('name_id');
        if (isset($conditions['count_query']) && !empty($conditions['count_query'])) {
            $count_query = 'COUNT(*) >= ' . $conditions['count_query'];
            $data->havingRaw($count_query);
        }
        $data->orderBy('id', 'ASC');
        $data = $data->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getArrYearData($academicyear, $condition) {
        $data = Model_arr_year::year($academicyear);
        $data->where($condition);
        $data = $data->first();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getAllArrYearData($academicyear) {
        $data = Model_arr_year::year($academicyear);
        $data = $data->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getSponsorDetail($academicyear, $pid) {
        $data = Model_arr_year::year($academicyear);
        $data->where('name_id', $pid);
        $data = $data->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getSponsorSchool($academicyear, $condition) {
        $sponsored_field_name = $condition['sponsored_school_id'];
        $sponsored_school_id = $condition['value'];

        $data = Model_arr_year::year($academicyear);
        $data->select('arr_year_' . $academicyear . '.name_id', 'population.firstname', 'population.lastname');
        $data->join('population', 'population.id', '=', 'arr_year_' . $academicyear . '.name_id');
        $data->where('arr_year_' . $academicyear . '.field', $sponsored_field_name);
        $data->where('arr_year_' . $academicyear . '.value', $sponsored_school_id);
        $data->groupBy('arr_year_' . $academicyear . '.name_id');
        $data = $data->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    public function getAllPupilData($year, $name_id_arr) {
        $getdata = Model_arr_year::year($year);
        $getdata->select('*');
        $getdata->whereIn('name_id', $name_id_arr);
        $data = $getdata->get();

        return $data;
    }
    public function getPupilData($year, $id) {
        $data = Model_arr_year::year($year)
                ->select('*')
                ->where('name_id', $id)
                ->get();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }
    public function getAllNameCode($academicyear) {
        $data = Model_arr_year::year($academicyear);
        $data->where('field', 'name_code');
        $data = $data->pluck('value','name_id')->toArray();
        $result = FALSE;
        if ($data) {
            $result = $data;
        }
        return $result;
    }
}
