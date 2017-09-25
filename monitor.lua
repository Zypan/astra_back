local hostname = utils.hostname()
_an = {}
_conf = {}
function redis_set(key, value)
--    log.info("/set/".. key .."/"..value)
  http_request({
    host    = "10.255.1.40",
    path    = "/set/".. key .."/"..value,
    method  = "GET",
    port    = 7379,
    headers = {
        "User-Agent: Astra v." .. astra.version,
        "Host: " .. hostname,
        "Connection: close",
    },
    callback = function(s,r)
    end
  })
end

function stream_analyze(cname, ctp, cid , addr)
id = tonumber (cid)
    _an[id] = {i = {}, a = {}}
    _conf[cid] = parse_url(addr)
    _conf[cid].name = "analyze"
    _an[id].i = init_input(_conf[cid])
    _an[id].a = analyze({
        upstream = _an[id].i.tail:stream(),
        name = "_"..cid,
        callback = function(data)
          if(data.total)then
            local scram = 0
            local onair = 0
            if(data.total.scrambled==true) then
              scram = 1
            end
            if(data.on_air==true) then
              onair = 1
            end
            content = "{'name':'" .. cname:gsub(" ","_") .. "','scrambled':" .. scram .. ",'bitrate':" .. data.total.bitrate .. ",'cc_error':" .. data.total.cc_errors .. ",'pes_error':" .. data.total.pes_errors .. ".'ready':" .. onair .."}"
--            log.info(content)
              redis_set("channel:" .. ctp .. ":" .. cid, content)
          end
        end
    })
end
