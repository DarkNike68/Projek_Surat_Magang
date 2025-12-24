<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $period_type
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @property-read Category|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category wherePeriodType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $file_path
 * @property string $file_name
 * @property int $category_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereUserId($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rak> $raks
 * @property-read int|null $raks_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lemari newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lemari newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lemari query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lemari whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lemari whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lemari whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lemari whereUpdatedAt($value)
 */
	class Lemari extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperLetterCode
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Surat> $suratAsJabatan
 * @property-read int|null $surat_as_jabatan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Surat> $suratAsJenis
 * @property-read int|null $surat_as_jenis_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LetterCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LetterCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LetterCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LetterCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LetterCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LetterCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LetterCode whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LetterCode whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LetterCode whereUpdatedAt($value)
 */
	class LetterCode extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $rak_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Rak $rak
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Surat> $surats
 * @property-read int|null $surats_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outner whereRakId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outner whereUpdatedAt($value)
 */
	class Outner extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $lemari_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Lemari $lemari
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Outner> $outners
 * @property-read int|null $outners_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rak query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rak whereLemariId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rak whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rak whereUpdatedAt($value)
 */
	class Rak extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperSurat
 * @property int $id
 * @property string $nomor_surat
 * @property int $nomor_urut_per_tahun
 * @property string $tahun
 * @property string $bulan_romawi
 * @property int $letter_code_jenis_surat_id
 * @property int $letter_code_jabatan_id
 * @property string|null $perihal
 * @property int $user_id
 * @property string|null $file_path
 * @property string|null $catatan
 * @property string $status
 * @property int|null $outner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LetterCode $jabatan
 * @property-read \App\Models\LetterCode $jenisSurat
 * @property-read \App\Models\Outner|null $outner
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereBulanRomawi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereLetterCodeJabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereLetterCodeJenisSuratId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereNomorSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereNomorUrutPerTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereOutnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereUserId($value)
 */
	class Surat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereUpdatedAt($value)
 */
	class Unit extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $unit_id
 * @property int $category_id
 * @property int $can_read
 * @property int $can_write
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitPermission whereCanRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitPermission whereCanWrite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitPermission whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitPermission whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitPermission whereUpdatedAt($value)
 */
	class UnitPermission extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperUser
 * @property int $id
 * @property int|null $unit_id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserPermissionException> $permissionExceptions
 * @property-read int|null $permission_exceptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Unit|null $unit
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int|null $can_read
 * @property int|null $can_write
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermissionException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermissionException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermissionException query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermissionException whereCanRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermissionException whereCanWrite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermissionException whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermissionException whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermissionException whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermissionException whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermissionException whereUserId($value)
 */
	class UserPermissionException extends \Eloquent {}
}

