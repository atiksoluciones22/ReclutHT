<?php

namespace App\Services;

class RequestService
{
    private $Handler;

    public function __construct()
    {
        $this->Handler = new Handler;
    }

    public function getTalentData($request, $append = [])
    {
        $name = strtoupper(get_array_value($request, 'personal_informationName'));

        $lastname = strtoupper(get_array_value($request, 'personal_informationLastname'));

        return array_merge([
            'NOM' => $name,
            'APE1' => $lastname,
            'APE2' => '',
            'NOMCOM' => $name . ' ' . $lastname,
            'APODO' => get_array_value($request, 'personal_informationName'),
            'EMAIL' => get_array_value($request, 'personal_informationEmail'),
            'TIPDOC' => get_array_value($request, 'personal_informationTypeDocument'),
            'CEDULA' => get_array_value($request, 'personal_informationNumDocument'),
            'CODPRO' => get_value_from_json_by_key(get_array_value($request, 'personal_informationProvince'), 'COD'),
            'CODMUN' => get_value_from_json_by_key(get_array_value($request, 'personal_informationMunicipality'), 'COD'),
            'CODDIS' => get_value_from_json_by_key(get_array_value($request, 'personal_informationDistrict'), 'COD'),
            'CODSEC' => get_value_from_json_by_key(get_array_value($request, 'personal_informationSection'), 'COD'),
            'SECTOR' => get_value_from_json_by_key(get_array_value($request, 'personal_informationSector'), 'COD'),
            'DIR' => get_array_value($request, 'personal_informationDomicile'),
            'TEL' => only_numbers(get_array_value($request, 'personal_informationTel')),
            'MOVIL' => only_numbers(get_array_value($request, 'personal_informationPhone')),
            'FECNTO' => format_date(get_array_value($request, 'personal_informationBirthdate')),
            'PAISOR' => get_array_value($request, 'personal_informationNationality'),
            'SEXO' => get_array_value($request, 'personal_informationSex'),
            'ESTCIV' => get_array_value($request, 'personal_informationCivilStatus'),
            'TIPLIC' => get_array_value($request, 'personal_informationCategory'),
            'RESTRI' => get_array_value($request, 'personal_informationRestrictions'),
            'ANTLIC' => format_date(get_array_value($request, 'personal_informationIssueDate')),
            'POSVEH' => get_array_value($request, 'personal_informationOwnVehicle'),
            'TRAEXT' => get_array_value($request, 'postulationWorkExterior'),
            'TRAINT' => get_array_value($request, 'postulationWorkInterior'),
            'TRAROT' => get_array_value($request, 'postulationWorkRotatingSchedule'),
            'NUMHIJ' => get_array_value($request, 'personal_informationCuantityChildren'),
            'NIVFOR' => get_array_value($request, 'skillLevelStudy'),
            'NOFOR' => get_array_value($request, 'skillLevelStudyOficial'),
            'SITUAC' => 1,
            'SUELDO' => format_money(get_array_value($request, 'postulationSalaryPretension')),
        ], $append);
    }

    public function getReferences($request)
    {
        return $this->Handler->groupAndTranslateValues($request,
        [
            'referenceName' => 'NOM',
            'referencePhone' => 'TELEFO',
            'referenceEmail' => 'EMAIL',
            'referenceProfession' => 'PROFES',
            'referenceType' => 'TIPO'
        ]);
    }

    public function getExperiences($request)
    {
        $experiences = $this->Handler->groupAndTranslateValues($request,
        [
            'experienceCompany' => 'EMP',
            'experienceStartDate' => 'FECINI',
            'experienceDepartureDate' => 'FECFIN',
            'experienceSalary' => 'SUELDO',
            'experiencePosition' => 'PUESTO',
            'experienceResponsibilities'  => 'RESPON',
            'experienceCountry' => 'PAIS',
            'experienceCity' => 'CIUDAD',
            'experienceWebDomain' => 'WEB',
            'experienceReasonDeparture' => 'MOTSAL',
        ]);


        foreach ($experiences as &$experience) {
            $experience['FECINI'] = format_date(get_array_value($experience, 'FECINI'));
            $experience['FECFIN'] = format_date(get_array_value($experience, 'FECFIN'));
            $experience['SUELDO'] = format_money(get_array_value($experience, 'SUELDO'));
        }

        return $experiences;
    }

    public function getLanguages($request)
    {
       $Languages = $this->Handler->groupAndTranslateValues($request,
        [
            'skillLanguage' => 'IDIOMA',
            'skillLanguageLevel' => 'NIVACR',
        ]);

        $Languages = filter_array_by_non_null_fields($Languages, ['IDIOMA']);

        return filter_array_by_unique_fields($Languages, ['IDIOMA']);
    }

    function getSkills($request)
    {
        $skills = $this->Handler->groupAndTranslateValues($request,
        [
            'skillProfessional' => 'HAB',
        ]);

        return $skills = array_values(array_unique($skills, SORT_REGULAR));
    }

    public function getBidRequests($request)
    {
        $getBidRequests = $this->Handler->groupAndTranslateValues($request,
        [
            'postulationPositionRequested' => 'OFERTA',
        ]);

        return filter_array_by_unique_fields($getBidRequests, ['OFERTA']);
    }
}
