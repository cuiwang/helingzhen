<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

defined("\x49\x4e\137\x49\x41") or die("\101\x63\x63\145\163\163\40\104\x65\156\x69\x65\144");
class Lonaking_flashModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        include $this->template("\163\x65\164\x74\151\156\147");
    }
}
