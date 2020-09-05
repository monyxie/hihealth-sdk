<?php


namespace Monyxie\HiHealth\Client;


use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Monyxie\HiHealth\Environment\EnvironmentInterface;
use Monyxie\HiHealth\Environment\Production;

class Client
{
    /**
     * @var string
     */
    private $accessToken;
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var EnvironmentInterface|null
     */
    private $environment;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->environment = new Production();
        $this->httpClient = new HttpClient();
    }

    /**
     * 查询用户每日运动汇总数据
     * @param int $startDate 查询开始日期，格式yyyyyMMdd。
     * @param int $endDate 查询结束日期，格式yyyyyMMdd
     * 如果查询单日数据，则查询开始时间等于查询结束时间。
     * 注：目前最大支持查询100天的数据
     * @return mixed
     * @throws ClientException
     */
    public function getSportsStat(int $startDate, int $endDate)
    {
        return $this->request('com.huawei.fit.getSportsStat', [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * 查询运动轨迹统计数据
     * @param int $startTime 查询开始的UTC时间戳，精确到毫秒。
     * @param int $endTime 查询结束的UTC时间戳，精确到毫秒。当查询特定时间的一条数据时，结束时间可以等于开始时间。
     * 注：目前最大支持查询10天的数据。
     * @return mixed
     * @throws ClientException
     */
    public function getMotionPathData(int $startTime, int $endTime)
    {
        return $this->request('com.huawei.fit.getMotionPathDetail', [
            'startTime' => $startTime,
            'endTime' => $endTime,
        ]);
    }

    /**
     * 查询用户每日健康汇总数据
     * @param int $startDate 查询开始日期，格式yyyyyMMdd。
     * @param int $endDate 查询结束日期，格式yyyyyMMdd
     * 如果查询单日数据，则查询开始时间等于查询结束时间。
     * 注：目前最大支持查询100天的数据
     * @param int $type 健康数据类型。目前支持取值为：
     * 7：心率
     * 9：科学睡眠数据
     * @return mixed
     * @throws ClientException
     */
    public function getHealthStat(int $startDate, int $endDate, int $type)
    {
        return $this->request('com.huawei.fit.getHealthStat', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type,
        ]);
    }

    /**
     * 查询用户健康明细数据
     * @param int $startTime 查询开始时间，为UTC时间，精确到毫秒。
     * @param int $endTime 查询结束日期，为UTC时间，精确到毫秒。 注：目前最大支持查询时间间隔为10天的数据。
     * @param int $type 健康数据类型。目前支持取值为：
     * 4：血糖
     * 5：血压
     * 7：心率
     * 8：体重体脂
     * 9：科学睡眠
     * @return mixed
     * @throws ClientException
     */
    public function getHealthData(int $startTime, int $endTime, int $type)
    {
        return $this->request('com.huawei.fit.getHealthData', [
            'startTime' => $startTime,
            'endTime' => $endTime,
            'type' => $type,
        ]);
    }

    /**
     * 新增用户健康明细数据
     * @param array $detailInfos 健康详情数据列表。目前只支持体重体脂数据的写入
     * 【注】单次最大支持上传50天的数据，超过50天需要由app侧进行拆分，分成多次请求进行调用。
     * @return mixed
     * @throws ClientException
     */
    public function addHealthData(array $detailInfos)
    {
        return $this->request('com.huawei.fit.addHealthData', [
            'detailInfos' => $detailInfos,
        ]);
    }

    /**
     * 查询用户信息
     * @param int $profileType 用户数据类型：
     * 0：查询所有信息
     * 1：用户基本信息
     * 2：用户设置目标
     * @return mixed
     * @throws ClientException
     */
    public function getUserInfo(int $profileType)
    {
        return $this->request('com.huawei.fit.getUserInfo', [
            'profileType' => $profileType,
        ]);
    }

    /**
     * 查询绑定设备信息
     * @param int $deviceCode 设备Id，不传返回全部绑定的设备。
     * @return mixed
     * @throws ClientException
     */
    public function getDeviceInfo(int $deviceCode)
    {
        return $this->request('com.huawei.fit.getDeviceInfo', [
            'deviceCode' => $deviceCode,
        ]);
    }

    /**
     * 绑定设备信息
     * @param int $productId 设备类型编号,跟华为健康云协商设备号，三方固定使用10001。
     * @param string $uniqueId 设备的唯一标识ID，由三方系统保证设备的唯一性，三方填入应用appid值。
     * @param string $manufacturer 设备制造商名称
     * @param string $firmwareVersion 固件版本号
     * @param string $hardwareVersion 硬件版本号
     * @param string $softwareVersion 软件版本号
     * @param string $name 设备名称
     * @return mixed
     * @throws ClientException
     */
    public function bindDeviceInfo(
        int $productId,
        string $uniqueId,
        string $manufacturer,
        string $firmwareVersion,
        string $hardwareVersion,
        string $softwareVersion,
        string $name
    )
    {
        return $this->request('com.huawei.fit.bindDeviceInfo', [
            'productId' => $productId,
            'uniqueId' => $uniqueId,
            'manufacturer' => $manufacturer,
            'firmwareVersion' => $firmwareVersion,
            'hardwareVersion' => $hardwareVersion,
            'softwareVersion' => $softwareVersion,
            'name' => $name
        ]);
    }

    /**
     * @param string $name
     * @param array|null $req
     * @param string|null $version
     * @param array|null $extra
     * @return mixed
     * @throws ClientException
     */
    private function request(string $name, array $req = null, string $version = null, array $extra = null)
    {
        $params = [
            'access_token' => $this->accessToken,
            'nsp_ts' => \time(),
            'nsp_svc' => $name,
        ];
        if ($extra !== null) {
            $params = \array_merge($extra, $params);
        }
        if ($req !== null) {
            $params['req'] = empty($req) ? '{}' : \json_encode($req, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        if ($version !== null) {
            $params['nsp_ver'] = $version;
        }

        $client = $this->getHttpClient();
        try {
            $response = $client->request('POST', $this->environment->getHealthUrl(), [
                'form_params' => $params
            ]);
        } catch (GuzzleException $e) {
            throw new ClientException('Request to Huawei cloud failed', 0, $e);
        }

        return \json_decode($response->getBody(), true);
    }

    /**
     * @return HttpClientInterface
     */
    private function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @param HttpClientInterface $httpClient
     * @return Client
     */
    public function setHttpClient(HttpClientInterface $httpClient): Client
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @return EnvironmentInterface|null
     */
    public function getEnvironment(): ?EnvironmentInterface
    {
        return $this->environment;
    }

    /**
     * @param EnvironmentInterface|null $environment
     * @return Client
     */
    public function setEnvironment(?EnvironmentInterface $environment): Client
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return Client
     */
    public function setAccessToken(string $accessToken): Client
    {
        $this->accessToken = $accessToken;
        return $this;
    }
}