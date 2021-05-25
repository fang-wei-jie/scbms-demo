@extends('layout.frame')

@section('title')
Accounts - Admin
@endsection

@section('body')
<div class="container">
    <form method="post" action="">
        @csrf

        <h5>Fill in at least 2 field for query</h5>

        <div class="form-row">
            <div class="col">
                <input class="form-control" type="text" name="name" id="name" placeholder="Name">
            </div>
            <div class="col">
                <input class="form-control" type="tel" name="phone" id="phone" placeholder="Phone Number">
            </div>
            <div class="col">
                <input class="form-control" type="email" name="email" id="email" placeholder="Email Address">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary disabled" type="submit" name="query" id="query" disabled>Query</button>
            </div>
        </div>
    </form>

    <br>

    @if($queried == 1)
        @if($count != 0)
            @foreach ($customer as $detail)
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5>{{ $detail->name }}</h5>
                        </div>
                        <div class="col">
                            <h6 class="card-subtitle text-muted">{{ $detail->phone }}</h6>
                        </div>
                        <div class="col">
                            <h6 class="card-subtitle text-muted">{{ $detail->email }}</h6>
                        </div>
                        <div class="col-auto">
                            <form action="" method="post">
                                @csrf
                                <input type="text" name="id" style="display: none;" value="{{ $detail->id }}">
                                <input type="text" name="generated_password" style="display: none;" value="{{ $detail->phone }}{{ $detail->email }}">
                                <button class="btn btn-warning" type="submit" id="reset_password" name="reset_password" disabled>Reset Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <div class="card">
            <div class="card-body text-white bg-warning">
                <h1 class="card-title">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    No record found, or details doesn't match up
                </h1>
            </div>
        </div>
        @endif
    @endif

    @if($queried == 2)
        <div class="card">
            <div class="card-body text-white bg-warning">
                <h1 class="card-title">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    Error
                </h1>
                <p class="card-text">Input parsing error, possibly violated requirement of at least 2 inputs. </p>
            </div>
        </div>
    @endif

</div>
@endsection

@section('bottom-js')
<script>
    $(document).ready(function(){
        $("input").change(function(){
            // check if at least two fields were entered input field values were chagned

            if (($("#name").val() != "" && $("#phone").val() != "") || ($("#name").val() != "" && $("#email").val() != "") || ($("#phone").val() != "" && $("#email").val() != "") || ($("#name").val() != "" && $("#phone").val() != "" && $("#email").val() != "")){

                // if minimum necessity is fulfilled
                $("#query").removeClass('disabled')
                $("#query").prop("disabled", false)

            } else {

                // if minimum necessity is not fulfilled
                $("#query").addClass('disabled')
                $("#query").prop("disabled", true)
            }
        })

        $("#reset_password").hover(function(){
            $(this).prop('disabled', false)
            $(this).prop('value', 'Confirm password reset?')
        })

        @if($reset ?? '' == 1)
        alert('Password reset successful. New password is phone and email address registered to this account. ')
        @elseif($reset ?? '' == 2)
        alert('Password reset failed')
        @endif
    })
</script>
@endsection
