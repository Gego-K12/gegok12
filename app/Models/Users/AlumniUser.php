<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models\Users;

use App\Models\User;
use Gegok12\Alumni\Models\Alumniprofile;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class AlumniUser
 *
 * Specialized User model for alumni-specific functionality.
 * Extends the base User model with alumni-focused relationships and business logic.
 *
 * @property-read Alumniprofile|null $alumniprofile
 *
 * @mixin \Eloquent
 */
class AlumniUser extends User
{
    /**
     * Scope to filter alumni by passing batch/year.
     *
     * @param  Builder  $query
     * @param  string  $passing_session
     * @return Builder
     */
    public function scopeByBatch($query, $passing_session)
    {
        return $query->wherehas('alumniprofile', function ($query) use ($passing_session) {
            $query->where('passing_session', $passing_session);
        });
    }

    /**
     * Get alumni education details.
     *
     * @return array
     */
    public function getEducationDetails()
    {
        $array = [];
        if (is_array($this->alumniprofile['institution_name'])) {
            for ($i = 0; $i < count($this->alumniprofile['institution_name']); $i++) {
                $array[$i][] = $this->alumniprofile['institution_name'][$i];
                $array[$i][] = $this->alumniprofile['degree'][$i];
                $array[$i][] = $this->alumniprofile['specialization'][$i];
                $array[$i][] = $this->alumniprofile['college_start_year'][$i] ?? null;
                $array[$i][] = $this->alumniprofile['college_end_year'][$i] ?? null;
                $array[$i][] = $this->alumniprofile['grade'][$i];
            }
        }

        return $array;
    }

    /**
     * Get alumni education details formatted for display.
     *
     * @return array
     */
    public function getEducationFormatted()
    {
        $array = [];
        if (is_array($this->alumniprofile['institution_name'])) {
            for ($i = 0; $i < count($this->alumniprofile['institution_name']); $i++) {
                $array['inputs'][$i]['institution_name'] = $this->alumniprofile['institution_name'][$i];
                $array['inputs'][$i]['degree'] = $this->alumniprofile['degree'][$i];
                $array['inputs'][$i]['specialization'] = $this->alumniprofile['specialization'][$i];
                $array['inputs'][$i]['college_start_year'] = $this->alumniprofile['college_start_year'][$i] ?? null;

                if (
                    ($this->alumniprofile['college_end_year'][$i] == null)
                    && ($this->alumniprofile['grade'][$i] == null)
                ) {
                    $array['inputs'][$i]['current_studying'] = 1;
                } else {
                    $array['inputs'][$i]['college_end_year'] = $this->alumniprofile['college_end_year'][$i] ?? null;
                    $array['inputs'][$i]['grade'] = $this->alumniprofile['grade'][$i];
                }
            }
        }

        return $array;
    }

    /**
     * Get alumni work experience details.
     *
     * @return array
     */
    public function getWorkDetails()
    {
        $array = [];
        if (is_array($this->alumniprofile['company_name'])) {
            for ($i = 0; $i < count($this->alumniprofile['company_name']); $i++) {
                $array[$i][] = $this->alumniprofile['company_name'][$i] ?? null;
                $array[$i][] = $this->alumniprofile['designation'][$i] ?? null;
                $array[$i][] = $this->alumniprofile['location'][$i] ?? null;
                $array[$i][] = $this->alumniprofile['job_start_year'][$i] ?? null;
                $array[$i][] = $this->alumniprofile['job_start_month'][$i] ?? null;
                $array[$i][] = $this->alumniprofile['job_end_year'][$i] ?? null;
                $array[$i][] = $this->alumniprofile['job_end_month'][$i] ?? null;
            }
        }

        return $array;
    }

    /**
     * Get alumni work experience details formatted for display.
     *
     * @return array
     */
    public function getWorkFormatted()
    {
        $array = [];
        if (is_array($this->alumniprofile['company_name'])) {
            for ($i = 0; $i < count($this->alumniprofile['company_name']); $i++) {
                $array['inputs'][$i]['company_name'] = $this->alumniprofile['company_name'][$i] ?? null;
                $array['inputs'][$i]['designation'] = $this->alumniprofile['designation'][$i] ?? null;
                $array['inputs'][$i]['location'] = $this->alumniprofile['location'][$i] ?? null;
                $array['inputs'][$i]['job_start_year'] = $this->alumniprofile['job_start_year'][$i] ?? null;
                $array['inputs'][$i]['job_start_month'] = $this->alumniprofile['job_start_month'][$i] ?? null;

                if (
                    ($this->alumniprofile['job_end_year'][$i] == null)
                    && ($this->alumniprofile['job_end_month'][$i] == null)
                ) {
                    $array['inputs'][$i]['present'] = 1;
                } else {
                    $array['inputs'][$i]['job_end_year'] = $this->alumniprofile['job_end_year'][$i] ?? null;
                    $array['inputs'][$i]['job_end_month'] = $this->alumniprofile['job_end_month'][$i] ?? null;
                }
            }
        }

        return $array;
    }

    /**
     * Backward compatibility alias for getEducationDetails().
     *
     * @return array
     */
    public function getAlumniEducationDetails()
    {
        return $this->getEducationDetails();
    }

    /**
     * Backward compatibility alias for getEducationFormatted().
     *
     * @return array
     */
    public function getAlumniEducation()
    {
        return $this->getEducationFormatted();
    }

    /**
     * Backward compatibility alias for getWorkDetails().
     *
     * @return array
     */
    public function getAlumniWorkDetails()
    {
        return $this->getWorkDetails();
    }

    /**
     * Backward compatibility alias for getWorkFormatted().
     *
     * @return array
     */
    public function getAlumniWork()
    {
        return $this->getWorkFormatted();
    }
}
