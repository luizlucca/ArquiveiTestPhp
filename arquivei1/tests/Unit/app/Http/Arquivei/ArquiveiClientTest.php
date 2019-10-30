<?php

namespace Tests\Unit;

use App\Http\Arquivei\ArquiveiClient;
use App\Http\Arquivei\ArquiveiClientInterface;
use App\Http\Helper\GuzzleHttpService;
use App\Http\Helper\HttpClientInterface;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class ArquiveiClientTest extends TestCase
{

    protected $arquiveiClient;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $mockGuzzleClient = $this->mock(HttpClientInterface::class);
        $mockGuzzleClient->shouldReceive('doGet')
            ->andReturn(new Response(
                $status = 200,
                $headers = [],
                '{"status": {"code": 200,"message": "Ok"},"data": [{"access_key": "35140330290824000104550010003715421390782397","xml": "PG5mZVByb2MgeG1sbnM9Imh0dHA6Ly93d3cucG9ydGFsZmlzY2FsLmluZi5ici9uZmUiIHZlcnNhbz0iMy4xMCI+PE5GZSB4bWxucz0iaHR0cDovL3d3dy5wb3J0YWxmaXNjYWwuaW5mLmJyL25mZSI+PGluZk5GZSB4bWxucz0iaHR0cDovL3d3dy5wb3J0YWxmaXNjYWwuaW5mLmJyL25mZSIgSWQ9Ik5GZTM1MTQwMzMwMjkwODI0MDAwMTA0NTUwMDEwMDAzNzE1NDIxMzkwNzgyMzk3IiB2ZXJzYW89IjMuMTAiPjxpZGU+PGNVRj4zNTwvY1VGPjxjTkY+MzkwNzgyMzk8L2NORj48bmF0T3A+VkVOREEgTk8gRVNUQURPIElDTVMtU1Q8L25hdE9wPjxpbmRQYWc+MTwvaW5kUGFnPjxtb2Q+NTU8L21vZD48c2VyaWU+MTwvc2VyaWU+PG5ORj4zNzE1NDI8L25ORj48ZGhFbWk+MjAxNC0wMy0xMlQxOTowNDowMC0wMzowMDwvZGhFbWk+PHRwTkY+MTwvdHBORj48aWREZXN0PjE8L2lkRGVzdD48Y011bkZHPjM1NTA1MDY8L2NNdW5GRz48dHBJbXA+MTwvdHBJbXA+PHRwRW1pcz4xPC90cEVtaXM+PGNEVj43PC9jRFY+PHRwQW1iPjE8L3RwQW1iPjxmaW5ORmU+MTwvZmluTkZlPjxpbmRGaW5hbD4wPC9pbmRGaW5hbD48aW5kUHJlcz45PC9pbmRQcmVzPjxwcm9jRW1pPjA8L3Byb2NFbWk+PHZlclByb2M+MS4wPC92ZXJQcm9jPjwvaWRlPjxlbWl0PjxDTlBKPjMwMjkwODI0MDAwMTA0PC9DTlBKPjx4Tm9tZT5IZWxlbmEgRWxldHJvbmljYTwveE5vbWU+PHhGYW50PkVzdGhlciBlIEhlbGVuYSBFbGV0cm9uaWNhIEx0ZGE8L3hGYW50PjxlbmRlckVtaXQ+PHhMZ3I+UlVBIElOQUNJTyBBTFZFUyBCQVJDRUxPUzwveExncj48bnJvPjQyMTwvbnJvPjx4QmFpcnJvPkpBUVVFTElORTwveEJhaXJybz48Y011bj4zMjA0OTA2PC9jTXVuPjx4TXVuPlNBTyBNQVRFVVM8L3hNdW4+PFVGPkVTPC9VRj48Q0VQPjI5OTM2MjM1PC9DRVA+PGNQYWlzPjEwNTg8L2NQYWlzPjx4UGFpcz5CUkFTSUw8L3hQYWlzPjxmb25lPjI3Mzg0NTgzMjQ8L2ZvbmU+PC9lbmRlckVtaXQ+PElFPjc3Mzg3NDYwNzwvSUU+PENSVD4zPC9DUlQ+PC9lbWl0PjxkZXN0PjxDTlBKPjk3NjAwMjEzMDAwMTk2PC9DTlBKPjx4Tm9tZT5FbXByZXNhPC94Tm9tZT48ZW5kZXJEZXN0Pjx4TGdyPkFWRU5JREEgQ0FSTE9TIEJPVEVMSE88L3hMZ3I+PG5ybz4xODY5PC9ucm8+PHhCYWlycm8+Q0VOVFJPPC94QmFpcnJvPjxjTXVuPjM1NDg5MDY8L2NNdW4+PHhNdW4+U0FPIENBUkxPUzwveE11bj48VUY+U1A8L1VGPjxDRVA+MTM1NjAyNTA8L0NFUD48Y1BhaXM+MTA1ODwvY1BhaXM+PHhQYWlzPkJSQVNJTDwveFBhaXM+PC9lbmRlckRlc3Q+PGluZElFRGVzdD4xPC9pbmRJRURlc3Q+PElFPjYzNzA1NTU1NTExNzwvSUU+PC9kZXN0PjxkZXQgbkl0ZW09IjEiPjxwcm9kPjxjUHJvZD4xMjQyMTQ8L2NQcm9kPjxjRUFOLz48eFByb2Q+VGFtcGEgUmVzZXJ2YXQmIzI0MztyaW8gRXhwYW5zJiMyMjc7byAgIFRDLTMwNjAvTUYzMDwveFByb2Q+PE5DTT44NzA4OTk5MDwvTkNNPjxDRk9QPjU0MDU8L0NGT1A+PHVDb20+UEM8L3VDb20+PHFDb20+NjwvcUNvbT48dlVuQ29tPjUuMDMwMDwvdlVuQ29tPjx2UHJvZD4zMC4xODwvdlByb2Q+PGNFQU5UcmliLz48dVRyaWI+UEM8L3VUcmliPjxxVHJpYj42PC9xVHJpYj48dlVuVHJpYj41LjAzMDA8L3ZVblRyaWI+PGluZFRvdD4xPC9pbmRUb3Q+PC9wcm9kPjxpbXBvc3RvPjxJQ01TPjxJQ01TNjA+PG9yaWc+MDwvb3JpZz48Q1NUPjYwPC9DU1Q+PC9JQ01TNjA+PC9JQ01TPjxQSVM+PFBJU05UPjxDU1Q+MDQ8L0NTVD48L1BJU05UPjwvUElTPjxDT0ZJTlM+PENPRklOU05UPjxDU1Q+MDQ8L0NTVD48L0NPRklOU05UPjwvQ09GSU5TPjwvaW1wb3N0bz48L2RldD48ZGV0IG5JdGVtPSIyIj48cHJvZD48Y1Byb2Q+NDQ1MjMxPC9jUHJvZD48Y0VBTj43ODk4MjMzNDMwNjYzPC9jRUFOPjx4UHJvZD5DdWJvIFJvZGEgVHJhc2VpcmEgICAgICAgICAgICBBTDU5OUE8L3hQcm9kPjxOQ00+ODcwODk5OTA8L05DTT48Q0ZPUD41NDA1PC9DRk9QPjx1Q29tPlBDPC91Q29tPjxxQ29tPjI8L3FDb20+PHZVbkNvbT41Mi4wMjAwPC92VW5Db20+PHZQcm9kPjEwNC4wNDwvdlByb2Q+PGNFQU5UcmliPjc4OTgyMzM0MzA2NjM8L2NFQU5UcmliPjx1VHJpYj5QQzwvdVRyaWI+PHFUcmliPjI8L3FUcmliPjx2VW5UcmliPjUyLjAyMDA8L3ZVblRyaWI+PGluZFRvdD4xPC9pbmRUb3Q+PC9wcm9kPjxpbXBvc3RvPjxJQ01TPjxJQ01TNjA+PG9yaWc+Mzwvb3JpZz48Q1NUPjYwPC9DU1Q+PC9JQ01TNjA+PC9JQ01TPjxQSVM+PFBJU05UPjxDU1Q+MDQ8L0NTVD48L1BJU05UPjwvUElTPjxDT0ZJTlM+PENPRklOU05UPjxDU1Q+MDQ8L0NTVD48L0NPRklOU05UPjwvQ09GSU5TPjwvaW1wb3N0bz48L2RldD48ZGV0IG5JdGVtPSI0Ij48cHJvZD48Y1Byb2Q+NTkxMDMzPC9jUHJvZD48Y0VBTj43ODk5MjQ0MjA2MTYyPC9jRUFOPjx4UHJvZD5NYW5ndWVpcmEgQ29tYnVzdCYjMjM3O3ZlbCAgICAgICAgIE1MTFVCVC03L0QwMzc4PC94UHJvZD48TkNNPjU5MDkwMDAwPC9OQ00+PENGT1A+NTQwNTwvQ0ZPUD48dUNvbT5NVDwvdUNvbT48cUNvbT4yNTwvcUNvbT48dlVuQ29tPjIuMDMwMDwvdlVuQ29tPjx2UHJvZD41MC43NTwvdlByb2Q+PGNFQU5UcmliPjc4OTkyNDQyMDYxNjI8L2NFQU5UcmliPjx1VHJpYj5NVDwvdVRyaWI+PHFUcmliPjI1PC9xVHJpYj48dlVuVHJpYj4yLjAzMDA8L3ZVblRyaWI+PGluZFRvdD4xPC9pbmRUb3Q+PC9wcm9kPjxpbXBvc3RvPjxJQ01TPjxJQ01TNjA+PG9yaWc+MDwvb3JpZz48Q1NUPjYwPC9DU1Q+PC9JQ01TNjA+PC9JQ01TPjxQSVM+PFBJU0FsaXE+PENTVD4wMTwvQ1NUPjx2QkM+NTAuNzU8L3ZCQz48cFBJUz4wLjY1PC9wUElTPjx2UElTPjAuMzM8L3ZQSVM+PC9QSVNBbGlxPjwvUElTPjxDT0ZJTlM+PENPRklOU0FsaXE+PENTVD4wMTwvQ1NUPjx2QkM+NTAuNzU8L3ZCQz48cENPRklOUz4zLjAwPC9wQ09GSU5TPjx2Q09GSU5TPjEuNTI8L3ZDT0ZJTlM+PC9DT0ZJTlNBbGlxPjwvQ09GSU5TPjwvaW1wb3N0bz48L2RldD48ZGV0IG5JdGVtPSI1Ij48cHJvZD48Y1Byb2Q+NTkxMDQxPC9jUHJvZD48Y0VBTj43ODk5MjQ0MjA2MjYxPC9jRUFOPjx4UHJvZD5NYW5ndWVpcmEgQ29tYnVzdCYjMjM3O3ZlbCAgICAgICAgIE1MTFVCVC05L0QwMzgyPC94UHJvZD48TkNNPjU5MDkwMDAwPC9OQ00+PENGT1A+NTQwNTwvQ0ZPUD48dUNvbT5NVDwvdUNvbT48cUNvbT4yNTwvcUNvbT48dlVuQ29tPjIuOTYwMDwvdlVuQ29tPjx2UHJvZD43NC4wMDwvdlByb2Q+PGNFQU5UcmliPjc4OTkyNDQyMDYyNjE8L2NFQU5UcmliPjx1VHJpYj5NVDwvdVRyaWI+PHFUcmliPjI1PC9xVHJpYj48dlVuVHJpYj4yLjk2MDA8L3ZVblRyaWI+PGluZFRvdD4xPC9pbmRUb3Q+PC9wcm9kPjxpbXBvc3RvPjxJQ01TPjxJQ01TNjA+PG9yaWc+MDwvb3JpZz48Q1NUPjYwPC9DU1Q+PC9JQ01TNjA+PC9JQ01TPjxQSVM+PFBJU0FsaXE+PENTVD4wMTwvQ1NUPjx2QkM+NzQuMDA8L3ZCQz48cFBJUz4wLjY1PC9wUElTPjx2UElTPjAuNDg8L3ZQSVM+PC9QSVNBbGlxPjwvUElTPjxDT0ZJTlM+PENPRklOU0FsaXE+PENTVD4wMTwvQ1NUPjx2QkM+NzQuMDA8L3ZCQz48cENPRklOUz4zLjAwPC9wQ09GSU5TPjx2Q09GSU5TPjIuMjI8L3ZDT0ZJTlM+PC9DT0ZJTlNBbGlxPjwvQ09GSU5TPjwvaW1wb3N0bz48L2RldD48ZGV0IG5JdGVtPSIzIj48cHJvZD48Y1Byb2Q+NzI3NzA5PC9jUHJvZD48Y0VBTi8+PHhQcm9kPkNvcnJlaWEgTXVsdGkgViAgICAgICAgICAgICAgIDZQSzEyMjA8L3hQcm9kPjxOQ00+NDAxMDMxMDA8L05DTT48Q0ZPUD41NDA1PC9DRk9QPjx1Q29tPlBDPC91Q29tPjxxQ29tPjQ8L3FDb20+PHZVbkNvbT4yNi43MzAwPC92VW5Db20+PHZQcm9kPjEwNi45MjwvdlByb2Q+PGNFQU5UcmliLz48dVRyaWI+UEM8L3VUcmliPjxxVHJpYj40PC9xVHJpYj48dlVuVHJpYj4yNi43MzAwPC92VW5UcmliPjxpbmRUb3Q+MTwvaW5kVG90PjwvcHJvZD48aW1wb3N0bz48SUNNUz48SUNNUzYwPjxvcmlnPjI8L29yaWc+PENTVD42MDwvQ1NUPjwvSUNNUzYwPjwvSUNNUz48UElTPjxQSVNBbGlxPjxDU1Q+MDE8L0NTVD48dkJDPjEwNi45MjwvdkJDPjxwUElTPjAuNjU8L3BQSVM+PHZQSVM+MC42OTwvdlBJUz48L1BJU0FsaXE+PC9QSVM+PENPRklOUz48Q09GSU5TQWxpcT48Q1NUPjAxPC9DU1Q+PHZCQz4xMDYuOTI8L3ZCQz48cENPRklOUz4zLjAwPC9wQ09GSU5TPjx2Q09GSU5TPjMuMjE8L3ZDT0ZJTlM+PC9DT0ZJTlNBbGlxPjwvQ09GSU5TPjwvaW1wb3N0bz48L2RldD48dG90YWw+PElDTVNUb3Q+PHZCQz4wLjAwPC92QkM+PHZJQ01TPjAuMDA8L3ZJQ01TPjx2SUNNU0Rlc29uPjAuMDA8L3ZJQ01TRGVzb24+PHZCQ1NUPjAuMDA8L3ZCQ1NUPjx2U1Q+MC4wMDwvdlNUPjx2UHJvZD4zNjUuODk8L3ZQcm9kPjx2RnJldGU+MC4wMDwvdkZyZXRlPjx2U2VnPjAuMDA8L3ZTZWc+PHZEZXNjPjA8L3ZEZXNjPjx2SUk+MDwvdklJPjx2SVBJPjAuMDA8L3ZJUEk+PHZQSVM+MS41MTwvdlBJUz48dkNPRklOUz42Ljk1PC92Q09GSU5TPjx2T3V0cm8+MC4wMDwvdk91dHJvPjx2TkY+MzY1Ljg5PC92TkY+PC9JQ01TVG90PjwvdG90YWw+PHRyYW5zcD48bW9kRnJldGU+MDwvbW9kRnJldGU+PHRyYW5zcG9ydGE+PENOUEo+MDY4MTk0MjgwMDAxNDc8L0NOUEo+PHhOb21lPlNlYW1sZXNzIFRyYW5zcG9ydGVzIEUgVHVyaXNtbzwveE5vbWU+PElFPjY4OTA0NTA0MDg3NzwvSUU+PHhFbmRlcj5SVUEgQ0VMIE1BUkNJTElPIEZSQU5DTyA4MDA8L3hFbmRlcj48eE11bj5GUkFOQ0E8L3hNdW4+PFVGPlNQPC9VRj48L3RyYW5zcG9ydGE+PHZvbD48cVZvbD4zPC9xVm9sPjxlc3A+U0M8L2VzcD48cGVzb0w+OS4xNDg8L3Blc29MPjxwZXNvQj45LjMzMTwvcGVzb0I+PC92b2w+PC90cmFuc3A+PC9pbmZORmU+PFNpZ25hdHVyZSB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC8wOS94bWxkc2lnIyI+PFNpZ25lZEluZm8+PENhbm9uaWNhbGl6YXRpb25NZXRob2QgQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy14bWwtYzE0bi0yMDAxMDMxNSIvPjxTaWduYXR1cmVNZXRob2QgQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwLzA5L3htbGRzaWcjcnNhLXNoYTEiLz48UmVmZXJlbmNlIFVSST0iI05GZTM1MTQwMzMwMjkwODI0MDAwMTA0NTUwMDEwMDAzNzE1NDIxMzkwNzgyMzk3Ij48VHJhbnNmb3Jtcz48VHJhbnNmb3JtIEFsZ29yaXRobT0iaHR0cDovL3d3dy53My5vcmcvMjAwMC8wOS94bWxkc2lnI2VudmVsb3BlZC1zaWduYXR1cmUiLz48VHJhbnNmb3JtIEFsZ29yaXRobT0iaHR0cDovL3d3dy53My5vcmcvVFIvMjAwMS9SRUMteG1sLWMxNG4tMjAwMTAzMTUiLz48L1RyYW5zZm9ybXM+PERpZ2VzdE1ldGhvZCBBbGdvcml0aG09Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvMDkveG1sZHNpZyNzaGExIi8+PERpZ2VzdFZhbHVlPmRkY2ZiYzhjOTY2MWY1ODNlMTE1MGY1NzEyZjI3YzFkYTJiNjAwMWM8L0RpZ2VzdFZhbHVlPjwvUmVmZXJlbmNlPjwvU2lnbmVkSW5mbz48U2lnbmF0dXJlVmFsdWU+bFZMaHY0Q29jOVpEZGk1ZkhhdkpXY0JWTEZ6dlgxdnBJOWJydmdBTjdtMll3dXJSRi9qTzE4bk45YXBZZjM2NzhiYzdvby9GNUdQRExWS1ovdlNndWIvT2ljRS9CdEUvdUxXVlVvd2tCOTZNMDZYUXFnK0lrNklLazBqNWNSRzVzYVlGQXpNTU5wWlVGeHhPd2dsYkkvckZwb052MjVqVFZlcWZ0Ujh1VDEwPTwvU2lnbmF0dXJlVmFsdWU+PEtleUluZm8+PFg1MDlEYXRhPjxYNTA5Q2VydGlmaWNhdGU+TUlJR1p6Q0NCVStnQXdJQkFnSUlKQWh0REE3ZGpOb3dEUVlKS29aSWh2Y05BUUVGQlFBd2RURUxNQWtHQTFVRUJoTUNRbEl4RXpBUkJnTlZCQW9UQ2tsRFVDMUNjbUZ6YVd3eE5qQTBCZ05WQkFzVExWTmxZM0psZEdGeWFXRWdaR0VnVW1WalpXbDBZU0JHWldSbGNtRnNJR1J2SUVKeVlYTnBiQ0F0SUZKR1FqRVpNQmNHQTFVRUF4TVFRVU1nVTBWU1FWTkJJRkpHUWlCMk1UQWVGdzB4TVRBNU16QXlNVEF3TURCYUZ3MHhOREE1TWpreU1UQXdNREJhTUlIdk1Rc3dDUVlEVlFRR0V3SkNVakVMTUFrR0ExVUVDQk1DVTFBeEVqQVFCZ05WQkFjVENWTkJUeUJRUVZWTVR6RVRNQkVHQTFVRUNoTUtTVU5RTFVKeVlYTnBiREUyTURRR0ExVUVDeE10VTJWamNtVjBZWEpwWVNCa1lTQlNaV05sYVhSaElFWmxaR1Z5WVd3Z1pHOGdRbkpoYzJsc0lDMGdVa1pDTVJZd0ZBWURWUVFMRXcxU1JrSWdaUzFEVGxCS0lFRXpNUkl3RUFZRFZRUUxFd2xCVWlCVFJWSkJVMEV4UmpCRUJnTlZCQU1UUFV4VlEwbFBJRk1nUkVsVFZGSkpRbFZKUkU5U1FTQkVSU0JRUlVOQlV5QlFRVkpCSUVGVlZFOVRJRXhVUkVFNk5UZ3lOemsyT1RZd01EQXhNVGN3Z1o4d0RRWUpLb1pJaHZjTkFRRUJCUUFEZ1kwQU1JR0pBb0dCQUplSFBwQmVhU1p4N2FYemJTaXAwNVNxU0JoVUI4Z05vdTlDRjU1Wnd4UHJ1bGNZTVhKeER1M0F6bVliWnNlOEl5UHF0L05hZTRDb3NLOUpSUUdjbVZLUnJYTVhWY0ZOV0tWRnF6cnJvUWdQRlRQcSt1L3NveFVObFBNVUkzOUUxNk0rZEpPWVBPNzBPcjArU2xrb0pvWm1ZNlppaXlPTFRYZzVzb0Q5aUFvVkFnTUJBQUdqZ2dNQ01JSUMvakFKQmdOVkhSTUVBakFBTUE0R0ExVWREd0VCL3dRRUF3SUY0REFkQmdOVkhTVUVGakFVQmdnckJnRUZCUWNEQWdZSUt3WUJCUVVIQXdRd0h3WURWUjBqQkJnd0ZvQVVtdDBpdHZaMzZVSnZTTUpSUUtCVzR2TjBQN3N3Z2JVR0ExVWRFUVNCclRDQnFvRVVTbFZPU1U5U1FFeFZRMGxQVXk1RFQwMHVRbEtnSGdZRllFd0JBd0tnRlJNVFRGVkRTVThnUVZKQlZVcFBJRXBWVGtsUFVxQVpCZ1ZnVEFFREE2QVFFdzQxT0RJM09UWTVOakF3TURFeE42QStCZ1ZnVEFFREJLQTFFek13TmpFeE1UazJPREE1T1RZMk56WTVPRGc1TURBd01EQXdNREF3TURBd01EQXdNREF3TVRJME5EVTFOREpUVTFBZ1UxQ2dGd1lGWUV3QkF3ZWdEaE1NTURBd01EQXdNREF3TURBd01GY0dBMVVkSUFSUU1FNHdUQVlHWUV3QkFnTUtNRUl3UUFZSUt3WUJCUVVIQWdFV05HaDBkSEE2THk5M2QzY3VZMlZ5ZEdsbWFXTmhaRzlrYVdkcGRHRnNMbU52YlM1aWNpOXlaWEJ2YzJsMGIzSnBieTlrY0dNd2dmTUdBMVVkSHdTQjZ6Q0I2REJLb0VpZ1JvWkVhSFIwY0RvdkwzZDNkeTVqWlhKMGFXWnBZMkZrYjJScFoybDBZV3d1WTI5dExtSnlMM0psY0c5emFYUnZjbWx2TDJ4amNpOXpaWEpoYzJGeVptSjJNUzVqY213d1JLQkNvRUNHUG1oMGRIQTZMeTlzWTNJdVkyVnlkR2xtYVdOaFpHOXpMbU52YlM1aWNpOXlaWEJ2YzJsMGIzSnBieTlzWTNJdmMyVnlZWE5oY21aaWRqRXVZM0pzTUZTZ1VxQlFoazVvZEhSd09pOHZjbVZ3YjNOcGRHOXlhVzh1YVdOd1luSmhjMmxzTG1kdmRpNWljaTlzWTNJdlUyVnlZWE5oTDNKbGNHOXphWFJ2Y21sdkwyeGpjaTl6WlhKaGMyRnlabUoyTVM1amNtd3dnWmtHQ0NzR0FRVUZCd0VCQklHTU1JR0pNRWdHQ0NzR0FRVUZCekFDaGp4b2RIUndPaTh2ZDNkM0xtTmxjblJwWm1sallXUnZaR2xuYVhSaGJDNWpiMjB1WW5JdlkyRmtaV2xoY3k5elpYSmhjMkZ5Wm1KMk1TNXdOMkl3UFFZSUt3WUJCUVVITUFHR01XaDBkSEE2THk5dlkzTndMbU5sY25ScFptbGpZV1J2WkdsbmFYUmhiQzVqYjIwdVluSXZjMlZ5WVhOaGNtWmlkakV3RFFZSktvWklodmNOQVFFRkJRQURnZ0VCQUJwcW1LN21MYVN4aDBSYzNFblh0ZENsSUdzUXA1djFYUXRJM1pFTm1FeVNhRFRrUk5wL0tZejljTTBObXh0cjNWc2llOVVidmZWQ3p4cG5FQ0tIdFBwdHQwZU5xVzd6M3gyaS9WUmRsN1lJSERTZThPWDBBYTE5NnBmUjk3SmIwSXROdXFlYWM5UUxNZlJEY1Z6elhIanhDN2dWRHdSbEtlWU5aQW1hbWtpTTg1OG94VUdqQ1lOM1hWUzRjTzFqRjdDSlFvU3M4UXFwenI3TUEyUFlMeEJmR0pSVCtyRlZiZllYZzQ1L2VXUzJ0MjRPaDF0SVJubGwvU1I1NElNeVl4aUw3b0ZuZ0p0ekE0aDdaZUpvMjRkY0tXaVZiVkNHaTg5T2c3cUdoc2wzanpqYTZTQnJUVjlxRVMwYjVMck9UTUwxc00yWnZjSS9TRWxNS0s1cGVSbz08L1g1MDlDZXJ0aWZpY2F0ZT48L1g1MDlEYXRhPjwvS2V5SW5mbz48L1NpZ25hdHVyZT48L05GZT48cHJvdE5GZSB2ZXJzYW89IjMuMTAiPjxpbmZQcm90IElkPSJJZDEzNTE0MDE1NDM0ODQwOCI+PHRwQW1iPjE8L3RwQW1iPjx2ZXJBcGxpYz5TUF9ORkVfUExfMDA4YjwvdmVyQXBsaWM+PGNoTkZlPjM1MTQwMzMwMjkwODI0MDAwMTA0NTUwMDEwMDAzNzE1NDIxMzkwNzgyMzk3PC9jaE5GZT48ZGhSZWNidG8+MjAxNC0wMy0xMlQxOTowNzo0MjwvZGhSZWNidG8+PG5Qcm90PjEzNTE0MDE1NDM0ODQwODwvblByb3Q+PGRpZ1ZhbD5kZGNmYmM4Yzk2NjFmNTgzZTExNTBmNTcxMmYyN2MxZGEyYjYwMDFjPC9kaWdWYWw+PGNTdGF0PjEwMDwvY1N0YXQ+PHhNb3Rpdm8+QXV0b3JpemFkbyBvIHVzbyBkYSBORi1lPC94TW90aXZvPjwvaW5mUHJvdD48L3Byb3RORmU+PC9uZmVQcm9jPg=="}],"page": {"next": "https://sandbox-api.arquivei.com.br/v1/nfe/received?limit=1&cursor=10","previous": "https://sandbox-api.arquivei.com.br/v1/nfe/received?limit=1&cursor=0"},"count": 1,"signature": "82591194af41bb10d51112b9375fda9950cc4473e301ee669af61a2075da2fba"}')
            );

        $this->arquiveiClient = $this->app->make(ArquiveiClientInterface::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAllNfes()
    {
        $nfeNotes = $this->arquiveiClient->getAllNfeNotes();
        $this->assertEquals(sizeof($nfeNotes), 1);
        $this->assertEquals($nfeNotes[0]->id, '35140330290824000104550010003715421390782397');
        $this->assertEquals($nfeNotes[0]->value, 365.89);
    }
}