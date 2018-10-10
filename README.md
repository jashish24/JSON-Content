# Node JSON Expose
This module will expose node data of type page as JSON.

JSON Data Path: **//{base_url}/json_deliver/{site_api_key}/node/{node_id}**

Just enable module and configure your Site API Key by visiting **admin/config/system/site-information** page. Once updated visit above path to get node data as JSON.

For example: Site base URL is www.json-content.com . You have updated Site API Key as "ndds43ssa45d" and you want to load node of type page with id **13** as JSON. The JSON Data Path will be as below:

http://www.json-content.com/json_deliver/ndds43ssa45d/node/13

There will be access denied response if client pass wrong Site API Key in JSON Data Path or it has not been updated in settings.