## LoRa Network Flowchart

| User's Job                                 | Gateway's Job                            | Server's Job                            |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
| User sends JSON packet via LoRa            | Gateway receives LoRa packet             | Server receives HTTP request            |
| {UID, URL}                                 | and parses it                            | and processes it                        |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
|                                            |                                          |                                         |
|                                            |                                          |                                         |
| 1. User sends JSON packet via LoRa         | 2. Gateway receives LoRa packet          | 3. Server receives HTTP request         |
|    {UID, URL}                              |    and parses it                         |    and processes it                     |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
|                                            |                                          |                                         |
| 4.Gateway forwards UID and URL to server   |                                          |                                         |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
|                                            |                                          |                                         |
| 5. Extract UID and URL from JSON packet    |                                          |                                         |
|    and send HTTP request to                |                                          |                                         |
|    MySQL database                          |                                          |                                         |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
|                                            |                                          |                                         |
| 7. Server checks Credit Limit for UID      |                                          |                                         |
|    If Credit Limit >= 1:                   |                                          |                                         |
|    - Deduct 1 from Credit Limit            |                                          |                                         |
|    - Send '1' to gateway                   |                                          |                                         |
|    If not, send '2' to gateway             |                                          |                                         |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
|                                            |                                          |                                         |
| 8. Gateway receives response from server   |                                          |                                         |
|    If '1' received:                        |                                          |                                         |
|    - Make HTTP request to URL              |                                          |                                         |
|    - Receive response from URL             |                                          |                                         |
|    If '2' received:                        |                                          |                                         |
|    - Interrupt process                     |                                          |                                         |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
|                                            |                                          |                                         |
| 9. Gateway acts based on server response:  |                                          |                                         |
|    - If '1' received, make HTTP request    |                                          |                                         |
|    to URL                                  |                                          |                                         |
|    - Receive response from URL             |                                          |                                         |
|    - Send response and UID to server       |                                          |                                         |
|    for logging                             |                                          |                                         |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
|                                            |                                          |                                         |
| 10. Gateway receives response from URL     |                                          |                                         |
|    and sends it along with UID to server   |                                          |                                         |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
|                                            |                                          |                                         |
| 11. Server receives response from          |                                          |                                         |
|    gateway and logs activity               |                                          |                                         |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
|                                            |                                          |                                         |
| 12. Server logs activity                   |                                          |                                         |
|                                            |                                          |                                         |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
|                                            |                                          |                                         |
| 13. Done                                   |                                          |                                         |
|--------------------------------------------|------------------------------------------|-----------------------------------------|
    
