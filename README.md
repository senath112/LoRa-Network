## LoRa Network Flowchart

### User
1. **Task**: Sends JSON data containing UID and URL.
2. **URL Format**: None, data sent through LoRa transmission.

### Gateway
1. **Task**: Receives JSON data from the user.
2. **Task**: Parses JSON data to extract UID and URL.
3. **Task**: Sends HTTP request to server to check balance.
4. **Task**: Depending on response, may send HTTP request to call URL.
5. **Task**: Sends response of URL call to server.

### Server
1. **Task**: Receives HTTP request from gateway to check balance.
2. **URL Format**: `http://xtreamdevelopers.lk/check_balance?uid=<UID>`
3. **Task**: Checks credit balance in MySQL table.
4. **Task**: If balance sufficient, deducts credit and sends '1', else sends '2'.
5. **Task**: Receives HTTP request from gateway with URL response.
6. **URL Format**: `http://xtreamdevelopers.lk/network_act?uid=<UID>&response=<response>`
7. **Task**: Logs activity in MySQL table.
