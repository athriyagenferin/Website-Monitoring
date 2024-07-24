<!DOCTYPE html>
<html lang="en">

@include('layouts.head')

<body>

        @include('layouts.sidebar')
        
        @yield('content')
		
        @include('layouts.setting')

	
    @include('layouts.script')

</body>
</html>