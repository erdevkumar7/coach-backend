namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'country_code',
        'phone_number',
        'subject',
        'message'
    ];

    public $timestamps = false;
}
