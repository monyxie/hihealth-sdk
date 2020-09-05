<?php


namespace Monyxie\HiHealth\OAuth;


abstract class Scopes
{
    const PROFILE = 'https://www.huawei.com/health/profile';
    const PROFILE_READONLY = 'https://www.huawei.com/health/profile.readonly';
    const SPORT = 'https://www.huawei.com/health/sport';
    const SPORT_READONLY = 'https://www.huawei.com/health/sport.readonly';
    const HEALTH_WGT = 'https://www.huawei.com/health/health.wgt';
    const HEALTH_WGT_READONLY = 'https://www.huawei.com/health/health.wgt.readonly';
    const HEALTH_SLP = 'https://www.huawei.com/health/health.slp';
    const HEALTH_SLP_READONLY = 'https://www.huawei.com/health/health.slp.readonly';
    const HEALTH_HR = 'https://www.huawei.com/health/health.hr';
    const HEALTH_HR_READONLY = 'https://www.huawei.com/health/health.hr.readonly';
    const HEALTH_ECG = 'https://www.huawei.com/health/health.ecg';
    const HEALTH_ECG_READONLY = 'https://www.huawei.com/health/health.ecg.readonly';
    const HEALTH_BG = 'https://www.huawei.com/health/health.bg';
    const HEALTH_BG_READONLY = 'https://www.huawei.com/health/health.bg.readonly';
    const MOTIONPATH = 'https://www.huawei.com/health/motionpath';
    const MOTIONPATH_READONLY = 'https://www.huawei.com/health/motionpath.readonly';
    const ACTIVITY = 'https://www.huawei.com/health/activity';
    const NOTIFY = 'https://www.huawei.com/health/notify';
    const PUSH = 'https://www.huawei.com/health/push';
    const HEALTH_BP = 'https://www.huawei.com/health/health.bp';
    const HEALTH_BP_READONLY = 'https://www.huawei.com/health/health.bp.readonly';
    const HEALTH_PS_READONLY = 'https://www.huawei.com/health/health.ps.readonly';
    const HEALTH_RT_READONLY = 'https://www.huawei.com/health/health.rt.readonly';
    const TRANSCRIPT_READONLY = 'https://www.huawei.com/health/transcript.readonly';
    const TRANSCRIPT = 'https://www.huawei.com/health/transcript';
    const HUAWEIID = 'https://www.huawei.com/health/huaweiid';
    const HUAWEIID_READONLY = 'https://www.huawei.com/health/huaweiid.readonly';
    const DEVICE = 'https://www.huawei.com/health/device';
    const DEVICE_READONLY = 'https://www.huawei.com/health/device.readonly';
}