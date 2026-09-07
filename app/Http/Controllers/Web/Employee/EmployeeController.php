<?php

namespace App\Http\Controllers\Web\Employee;

use App\Core\Repository\Employee\EmployeeRepository;
use App\Core\Services\FileManager\Interface\FileManagerInterface;
use App\Http\Requests\Employee\EmployeeProfileRequest;
use App\Http\Requests\User\UserProfileRequest;
use App\Models\Users\Employee;
use App\Models\Users\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class EmployeeController extends Controller
{
    private EmployeeRepository $employeeRepository;
    private FileManagerInterface $fileManagerService;

    public function __construct(
        EmployeeRepository $employeeRepository,
        FileManagerInterface $fileManagerService,
    )
    {
        $this->employeeRepository = $employeeRepository;
        $this->fileManagerService = $fileManagerService;
    }

    /**
     * @return Factory|\Illuminate\Foundation\Application|View|Application
     */
    public function profile(): Factory|\Illuminate\Foundation\Application|View|Application
    {
        $user = Auth::user();
        return view('pages.employee.profile', [
            'employee' => $this->employeeRepository->getEmployeeProfile($user->id)
        ]);
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function updateProfile(): Factory|View|\Illuminate\Foundation\Application|Application
    {
        $user = Auth::user();
        return view('pages.employee.edit-profile', [
            'employee' => $this->employeeRepository->getEmployeeProfile($user->id)
        ]);
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function updateUser(): Factory|View|\Illuminate\Foundation\Application|Application
    {
        $user = Auth::user();
        return view('pages.employee.edit-user', [
            'employee' => $this->employeeRepository->getEmployeeProfile($user->id)
        ]);
    }

    /**
     * @param EmployeeProfileRequest $profileRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function editProfile(EmployeeProfileRequest $profileRequest, int $id): RedirectResponse
    {
        // URL'dagi `id` ishonchsiz: ilgari istalgan xodim boshqa
        // xodimning profilini tahrirlay olardi. Tahrirlanadigan yozuv
        // faqat autentifikatsiyadan o'tgan foydalanuvchinikidir.
        $this->assertOwnEmployee($id);

        $model = $this->findModel($id);
        $model->fill($profileRequest->validated());
        if(!empty($profileRequest->file('file'))){
            $file = $this->fileManagerService->image($profileRequest->file('file'));
            $model->setFileId($file->id);
            $model->setFileName($file->orginal_name);
        }
        try {
            $this->employeeRepository->save($model);
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('employee.profile');
    }

    public function editUser(UserProfileRequest $userRequest, int $id): RedirectResponse
    {
        // Xuddi shunday: login ma'lumotlari faqat o'z hisobiga tegishli
        // bo'lishi mumkin.
        $this->assertOwnEmployee($id);

        $model = $this->findEmployeeUser($id);
        $model->fill($userRequest->validated());
        try {
            $model->save();
        } catch (\RuntimeException $e) {
            return back()->with('exception', $e->getMessage());
        }

        Session::flash('success', __('client.Successful updated'));

        return redirect()->route('employee.profile');
    }

    public function findModel(int $id): Employee|Builder
    {
        return $this->employeeRepository->get($id);
    }

    /**
     * URL'dagi `id` joriy foydalanuvchining xodim yozuviga tegishlimi.
     *
     * Ikkala forma ham `users.employee_id` ni yuboradi
     * (resources/views/pages/employee/edit-{profile,user}.blade.php),
     * shuning uchun taqqoslash aynan shu ustun bo'yicha bajariladi.
     *
     * @param int $id
     * @return void
     */
    protected function assertOwnEmployee(int $id): void
    {
        /**
         * @var User|null $user
         */
        $user = Auth::user();

        if (empty($user) || empty($user->employee_id) || (int)$user->employee_id !== $id) {
            abort(403, __('client.Unauthorized action.'));
        }
    }

    public function findEmployeeUser(int $id): User|Builder
    {
        return $this->employeeRepository->getEmployeeUser($id);
    }
}
