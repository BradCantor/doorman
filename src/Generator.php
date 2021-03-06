<?php

namespace Clarkeash\Doorman;

use Carbon\Carbon;
use Clarkeash\Doorman\Models\Invite;
use Illuminate\Support\Str;

class Generator
{
    protected $amount = 1;
    protected $uses = 1;
    protected $email;
    protected $expiry;


    /**
     * @param int $amount
     *
     * @return $this
     */
    public function times($amount = 1)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function uses($amount = 1)
    {
        $this->uses = $amount;

        return $this;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
//     public function for ($email)
//     {
//         $this->email = $email;

//         return $this;
//     }
    
     /**
     * @param string $email
     *
     * @return $this
     */
    public function forEmail ($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param \Carbon\Carbon $date
     *
     * @return $this
     */
    public function expiresOn($date)
    {
        $this->expiry = $date;

        return $this;
    }

    /**
     * @param int $days
     *
     * @return $this
     */
    public function expiresIn($days = 14)
    {
        $this->expiry = Carbon::now(config('app.timezone'))->addDays($days)->endOfDay();

        return $this;
    }

    /**
     * @return \Clarkeash\Doorman\Models\Invite
     */
    protected function build()
    {
        $invite = new Invite;
        $invite->code = Str::upper(Str::random(5));
        $invite->for = $this->email;
        $invite->max = $this->uses;
        $invite->valid_until = $this->expiry;

        return $invite;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function make()
    {
        $invites = collect();

        for ($i = 0; $i < $this->amount; $i++) {
            $invite = $this->build();

            $invites->push($invite);

            $invite->save();
        }

        return $invites;
    }
}
