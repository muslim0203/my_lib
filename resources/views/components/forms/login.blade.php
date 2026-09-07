<div>
    <form method="POST" action="{{ $action }}" class="needs-validation">
        @csrf
        <h1> {{ __('client.Login') }} </h1>
        <p class="text-medium-emphasis">{{__('client.Your Personal Profile')}}</p>
        <div class="input-group mb-3"><span class="input-group-text"></span>
            <input
                class="form-control @error('username') is-invalid @else is-valid @enderror"
                type="text"
                value="{{old('username')}}"
                placeholder="{{ __('client.Username') }}"
                name="username"
            >
        </div>
        <div class="input-group mb-4"><span class="input-group-text"></span>
            <input
                class="form-control @error('password') is-invalid @else is-valid @enderror"
                type="password"
                value="{{old('password')}}"
                placeholder="{{ __('client.Password') }}"
                name="password"
            >
        </div>
        <div class="row">
            <div class="col-6">
                <button class="btn btn-primary px-4"
                        type="submit">{{ __('client.Login') }}</button>
            </div>
            <div class="col-6 text-end">
                <button class="btn btn-link px-0"
                        type="button">{{ __('client.Forgot password ?') }}</button>
            </div>
        </div>
    </form>
</div>
