<?php

function isAuthenticated()
{
    if(isset($_COOKIE['id']) and isset($_COOKIE['hash']))
    {
        return true;
    }
    else
    {
        return false;
    }
}