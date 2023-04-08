<?php

namespace Azuriom\Plugin\Votingwallet\Models;

use Azuriom\Models\Server;
use Azuriom\Models\Traits\HasImage;
use Azuriom\Models\Traits\HasTablePrefix;
use Azuriom\Models\Traits\Loggable;
use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;

class VotingWithdraw extends Model
{
    use HasImage;
    use HasTablePrefix;
    use Loggable;

    /**
     * The table prefix associated with the model.
     *
     * @var string
     */
    protected $table = 'vote_withdraw';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount_token', 'amount_euro'
    ];

    static function get_user_name ($id) {
        return User::where('id', $id)->value('name');
    }

    static function giveReward(User $user, $commands, $server_id)
    {
        $server = Server::where('id', $server_id)->first();
        $server->bridge()->sendCommands($commands, $user, true);
    }

}
