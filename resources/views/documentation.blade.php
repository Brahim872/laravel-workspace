<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: "Lato", sans-serif;
        }

        .sidenav {
            height: 100%;
            width: 160px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            padding-top: 20px;
        }

        .sidenav a {
            padding: 6px 8px 6px 16px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
        }

        .sidenav a:hover {
            color: #f1f1f1;
        }

        .main {
            margin-left: 160px; /* Same as the width of the sidenav */
            font-size: 20px; /* Increased text to enable scrolling */
            padding: 0px 10px;
        }

        code {
            background-color: #1a202c;
            width: 100%;
            padding: 10px 50% 10px 1%;
            color: #cbd5e0;
            border-radius: 0.51rem;
        }

        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<div class="sidenav">
    <a href="#login">login</a>
    <a href="#services">Services</a>
    <a href="#clients">Clients</a>
    <a href="#contact">Contact</a>
</div>

<div class="main">
    <section id="login">

        <fieldset>
            <legend>login</legend>
            <h4>Route</h4>
            <code>api/login</code>

            <h4>Return</h4>
            <div>
                <ul>
                    <li><b>success</b></li>
{{--                    <li>--}}
                        <ol>
                            <li>
                                <img src="{{asset('documentation/loginsuccess.webp')}}" alt="">
                            </li>
                        </ol>
{{--                    </li>--}}
                </ul>
            </div>

        </fieldset>
    </section>
</div>

</body>
</html>
