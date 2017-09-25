#Очередная ситема мониторинга для Astra v.4

Отсылка состояния адаптеров и каналов в Redis  
Требования:   
    - [redis v.3xx](https://redis.io/)  
    - [webdis](http://webd.is/)  
    
---
convert.php - скрипт конвертирования конфигов астра 5 в астра 4  
monitor.lua - служебный модуль для отправки мониринга   

##Пример использования   


    local hostname = utils.hostname()
    local configname = "astra"
    
    package.path = package.path..";/etc/astra/?.lua;"
    require "modules.monitor"
    
    
    a001 = dvb_tune({
        type    = "S2",
        name    = "test",
        tp      = "10832:H:22000",
        adapter = "6",
        callback = function(data)
            content = "{'tp':'10832:H:22000','server':" .. hostname .. ",'signal:" .. data.signal .. ",'snr':" .. data.snr .. ",'status':" .. data.status .. ",'ber':" .. data.ber .. ",'unc':" .. data.unc.."}"
            redis_set("dvb:"..configname..":a001",content)
        end
    })

    make_channel({
        name    = "RTL HD",
        id      = "a002",
        enable  = true,
        input   = { "dvb://a001#pnr=61200&no_analyze" },
        output  = { "http://10.255.1.40:9021/play/a002" },
    })


    stream_analyze("RTL HD","a001", 61200, "http://10.255.1.40:9021/play/a002")


    