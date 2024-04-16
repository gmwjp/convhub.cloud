<?php
namespace App\Libraries;
use GuzzleHttp\Client;
class ZendeskExporter {
    private $client;
    private function formatDate($date) {
        return $date->format('Y-m-d');
    }

    public function exportTickets($data, $username, $token,$controller) {
        set_time_limit(0);
        $this->outputFile = 'tickets_with_comments_' . $data["domain"] . '.json';
        $this->progressFile = WRITEPATH.'files/progress_' . $data["domain"] . '.txt';
        $this->client = new Client([
            'base_uri' => "https://".$data["domain"].".zendesk.com/api/v2/",
            'auth' => [$username . '/token', $token],
        ]);

        $startDate = file_exists($this->progressFile) ? new \DateTime(file_get_contents($this->progressFile)) : new \DateTime('2023-04-01');
        $today = new \DateTime();
        
        while ($startDate < $today) {
            $endDate = clone $startDate;
            $endDate->modify('+1 days');
            $page = 1;
            $query = 'type:ticket status:closed created>=' . $this->formatDate($startDate) . ' created<' . $this->formatDate($endDate);
            while (true) {
                if($page <= 10){

                    $response = $this->client->request('GET', 'search.json', ['query' => ['query' => $query, 'page' => $page]]);
                    $tickets = json_decode($response->getBody()->getContents(), true);
                    
                    if (empty($tickets['results'])) {
                        break;
                    }

                    foreach ($tickets['results'] as $ticket) {
                        $userResponse = $this->client->request('GET', "users/{$ticket['requester_id']}.json");
                        $user = json_decode($userResponse->getBody()->getContents(), true);
                        $mail = "";
                        $user_id = "";
                        if(isset($user['user']['email'])){
                            $mail = $user['user']['email'];
                        }
                        if(isset($user['user']['id'])){
                            $user_id = $user['user']['id'];
                        }
                        // コメント取得とチケットデータ処理
                        $commentsResponse = $this->client->request('GET', "tickets/{$ticket['id']}/comments.json");
                        $comments = json_decode($commentsResponse->getBody()->getContents(), true);
                        $ticketData = [
                            'ticket_id' => $ticket['id'],
                            'subject' => $ticket['subject'],
                            'body' => $ticket["description"],
                            'created' => $ticket["created_at"],
                            "mail" => $mail,
                            'comments' => [],
                        ];
                        foreach ($comments['comments'] as $comment) {
                            if($user_id == @$comment["author_id"]){
                                $section = "customer";
                            } else {
                                $section = "agent";
                            }
                            $ticketData['comments'][] = [
                                'comment_id' => $comment['id'],
                                'created' => $comment["created_at"],
                                'public' => $comment["public"],
                                'user_section' => $section,
                                'body' => $comment['body'],
                            ];
                        }
                        // データの追記
                        //file_put_contents($this->outputFile, json_encode($ticketData, JSON_PRETTY_PRINT) . ",\n", FILE_APPEND);
                        //データの保存
                        unset($dat);
                        $dat["form_id"] = $data["form_id"];
                        $dat["subform_id"] = $data["subform_id"];
                        $dat["mail"] = $ticketData["mail"];
                        $dat["title"] = $ticketData["subject"];
                        $dat["body"] = $ticketData["body"];
                        $dat["created"] = $ticketData["created"];
                        $dat["status"] = 2;
                        $dat["user_id"] = -99;
                        $dat["team_id"] = 1;
                        $dat["query_params"] = json_encode(["zendesk_ticket_id"=>$ticketData["ticket_id"]]);
                        $ticket_id = $controller->model("Tickets")->write($dat);
                        foreach($ticketData['comments'] as $key => $comm){
                            if($key != 0){
                                unset($dat);
                                $dat["ticket_id"] = $ticket_id;
                                $dat["body"] = $comm["body"];
                                $dat["user_id"] = -99;
                                if($comm["user_section"] == "customer"){
                                    $dat["user_section"] = "customer";
                                } else {
                                    $dat["user_section"] = "user";
                                }
                                $dat["created"] = $comm["created"];
                                if($comm["public"] == "TRUE"){
                                    $dat["public_flg"] = 1;
                                } else {
                                    $dat["public_flg"] = 0;
                                }
                                $controller->model("Comments")->write($dat);    
                            } else {
                                unset($dat);
                                $dat["id"] =$ticket_id;
                                $dat["body"] = $comm["body"];
                                $controller->model("Tickets")->write($dat);
                            }
                        }
                    }
                    $page++;
                    sleep(1);
                } else {
                    break;
                }
            }
            $startDate->modify('+1 days');
            file_put_contents($this->progressFile, $this->formatDate($startDate));
        }
    }
}