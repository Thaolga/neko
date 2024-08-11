<?php

$dt=json_decode((shell_exec("ubus call system board")), true);
// MACHINE INFO
$devices=$dt['model'];

// OS TYPE AND KERNEL VERSION
$kernelv=exec("cat /proc/sys/kernel/ostype").' '.exec("cat /proc/sys/kernel/osrelease");
$OSVer=$dt['release']['distribution']." ".$dt['release']['version'];

// MEMORY INFO
$tmpramTotal=exec("cat /proc/meminfo | grep MemTotal | awk '{print $2}'");
$tmpramAvailable=exec("cat /proc/meminfo | grep MemAvailable | awk '{print $2}'");

$ramTotal=number_format(($tmpramTotal/1000),1);
$ramAvailable=number_format(($tmpramAvailable/1000),1);
$ramUsage=number_format((($tmpramTotal-$tmpramAvailable)/1000),1);

// UPTIME
$raw_uptime = exec("cat /proc/uptime | awk '{print $1}'");
$days = floor($raw_uptime / 86400);
$hours = floor(($raw_uptime / 3600) % 24);
$minutes = floor(($raw_uptime / 60) % 60);
$seconds = $raw_uptime % 60;


// CPU FREQUENCY
/*  $cpuFreq = file_get_contents("/sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq");
$cpuFreq = round($cpuFreq / 1000, 1);

// CPU TEMPERATURE
$cpuTemp = file_get_contents("/sys/class/thermal/thermal_zone0/temp");
$cpuTemp = round($cpuTemp / 1000, 1);
if ($cpuTemp >= 60) {
    $color = "red";
} elseif ($cpuTemp >= 50) {
    $color = "orange";
} else {
    $color = "white";
}
// 设置时区为北京时间
date_default_timezone_set('Asia/Shanghai');
// 获取当前日期和时间
$currentDateTime = new DateTime();
$formattedDateTime = $currentDateTime->format('Y年m月d日 H时i分s秒');
*/
// OP Processor Architecture
$processorArch = shell_exec("uname -m");

$cpuModel = shell_exec("cat /proc/cpuinfo | grep 'model name' | uniq");
$cpuModel = trim(preg_replace('/.*: /', '', $cpuModel));

$cpuThreads = shell_exec("nproc");

// CPU LOAD AVERAGE
$cpuLoad = shell_exec("cat /proc/loadavg");
$cpuLoad = explode(' ', $cpuLoad);
$cpuLoadAvg1Min = round($cpuLoad[0], 2);
$cpuLoadAvg5Min = round($cpuLoad[1], 2);
$cpuLoadAvg15Min = round($cpuLoad[2], 2);

// CPU INFORMATION
/* $cpuInfo = shell_exec("lscpu");
$cpuCores = preg_match('/^CPU\(s\):\s+(\d+)/m', $cpuInfo, $matches);
$cpuThreads = preg_match('/^Thread\(s\) per core:\s+(\d+)/m', $cpuInfo, $matches);
$cpuModelName = preg_match('/^Model name:\s+(.+)/m', $cpuInfo, $matches);
$cpuFamily = preg_match('/^CPU family:\s+(.+)/m', $cpuInfo, $matches);
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Music Player</title>

<style>
    #timeDisplay {
        font-size: 30px;
        font-weight: bold;
         color: #4CAF50; 
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        overflow: hidden; 
    }

    #player {
        width: 320px;
        height: 320px; 
        margin: 50px auto;
        padding: 20px;     
        background: url('/nekoclash/assets/img/3.svg') no-repeat center center;
        background-size: cover;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        border-radius: 50%;
        transform-style: preserve-3d; 
        transition: transform 0.5s; 
        position: relative;
        animation: rainbow 5s infinite, rotatePlayer 10s linear infinite;
    }

    #player:hover {
        transform: rotateY(360deg) rotateX(360deg);
    }

    #player h2 {
        margin-top: 0;
    }

    #controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        
    }

    button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        box-shadow: 0 4px #666;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    button:active {
        transform: translateY(4px);
        box-shadow: 0 2px #444;
    }

    @keyframes rainbow {
        0% {background-color: red;}
        10% {background-color: orange;}
        20% {background-color: yellow;}
        30% {background-color: #4CAF50;} 
        40% {background-color: cyan;}
        50% {background-color: blue;}
        60% {background-color: indigo;}
        70% {background-color: violet;}
        80% {background-color: magenta;}
        90% {background-color: pink;}
        100% {background-color: red;}
    }

    @keyframes rotatePlayer {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes fall {
        0% {
            transform: translateY(0) translateX(0);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) translateX(calc(-50% + 50vw));
            opacity: 0;
        }
    }

    .petal {
            position: absolute;
            top: 0;
            width: 20px;
            height: 20px;
            background: pink;
            border-radius: 50%;
            animation: fall linear;
        }

       #hidePlayer {
        font-size: 20px;
        ont-weight: bold;
        color: red; 
        text-align: center;
        margin-bottom: 20px; 
        
    }
	.rounded-button {
            border-radius: 30px 15px;
        }
		
</style>

</head>
<body>
    <div id="player" onclick="toggleAnimation()"> 
     <p id="hidePlayer" >隐藏播放</p>
            <p id="timeDisplay">00:00 </p>
        <audio id="audioPlayer" controls autoplay >  
            <source src="" type="audio/mpeg">
            您的浏览器不支持音频播放。
        </audio>
      <br>
     <div id="controls">
        <button id="prev" class="rounded-button">⏮️</button>
        <button id="orderLoop" class="rounded-button">🔁</button>
        <button id="play" class="rounded-button">⏸️</button>
        <button id="next" class="rounded-button">⏭️</button>        
    </div>  

    <script>
       
        /* var player = document.getElementById('player');
        var offsetX, offsetY, isDragging = false;

        player.addEventListener('mousedown', function(e) {
            isDragging = true;
            offsetX = e.clientX - player.offsetLeft;
            offsetY = e.clientY - player.offsetTop;
            player.style.cursor = 'grabbing';
        });

        document.addEventListener('mousemove', function(e) {
            if (isDragging) {
                player.style.left = (e.clientX - offsetX) + 'px';
                player.style.top = (e.clientY - offsetY) + 'px';
            }
        });

        document.addEventListener('mouseup', function() {
            isDragging = false;
            player.style.cursor = 'grab';
        }); */

       document.addEventListener('keydown', function(event) {
            switch(event.key) {
                case 'ArrowLeft': 
                    document.getElementById('prev').click();
                    break;
                case 'ArrowRight': 
                    document.getElementById('next').click();
                    break;
                case ' ': 
                    document.getElementById('play').click();
                    break;
                case 'ArrowUp': 
                    document.getElementById('orderLoop').click();
                    break;
            }
        });
        var hidePlayerButton = document.getElementById('hidePlayer');
        hidePlayerButton.addEventListener('click', function() {
        var player = document.getElementById('player');
        if (player.style.display === 'none') {
            player.style.display = 'flex';
        } else {
            player.style.display = 'none';
          }
        });
        function toggleAnimation() {
            const player = document.getElementById('player');
            if (player.style.animationPlayState === 'paused') {
                player.style.animationPlayState = 'running'; 
            } else {
                player.style.animationPlayState = 'paused'; 
            }
        }
        function createPetal() {
            const petal = document.createElement('div');
            petal.className = 'petal';
            petal.style.left = Math.random() * 100 + 'vw';
            petal.style.animationDuration = Math.random() * 3 + 2 + 's';
            document.body.appendChild(petal);

            petal.addEventListener('animationend', () => {
                petal.remove();
            });
        }

        setInterval(createPetal, 50);
        function clearAllPetals() {
            const petals = document.querySelectorAll('.petal');
            petals.forEach(petal => petal.remove());
        }

        setTimeout(clearAllPetals, 10000);

        function updateTime() {
            var now = new Date();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var seconds = now.getSeconds().toString().padStart(2, '0');
            document.getElementById('timeDisplay').innerText = hours + ':' + minutes + ':' + seconds + ' ' + getAncientTime(now);
        }

        function getAncientTime(date) {
            const hours = date.getHours();
            let ancientTime;

            if (hours >= 23 || hours < 1) {
                ancientTime = '子時';
            } else if (hours >= 1 && hours < 3) {
                ancientTime = '丑時';
            } else if (hours >= 3 && hours < 5) {
                ancientTime = '寅時';
            } else if (hours >= 5 && hours < 7) {
                ancientTime = '卯時';
            } else if (hours >= 7 && hours < 9) {
                ancientTime = '辰時';
            } else if (hours >= 9 && hours < 11) {
                ancientTime = '巳時';
            } else if (hours >= 11 && hours < 13) {
                ancientTime = '午時';
            } else if (hours >= 13 && hours < 15) {
                ancientTime = '未時';
            } else if (hours >= 15 && hours < 17) {
                ancientTime = '申時';
            } else if (hours >= 17 && hours < 19) {
                ancientTime = '酉時';
            } else if (hours >= 19 && hours < 21) {
                ancientTime = '戌時';
            } else {
                ancientTime = '亥時';
            }

            return ancientTime;
        }

        setInterval(updateTime, 1000); 
        window.onload = updateTime;

        var audioPlayer = document.getElementById('audioPlayer');
        var playButton = document.getElementById('play');
        var nextButton = document.getElementById('next');
        var prevButton = document.getElementById('prev');
        var orderLoopButton = document.getElementById('orderLoop');
        var orderButton = document.getElementById('order'); 
        var isLooping = false; 
        var isOrdered = false; 
        var currentSongIndex = 0;
        var songs = [];

        fetch('https://raw.githubusercontent.com/Thaolga/Rules/main/Clash/songs.txt')
           .then(response => response.text())
           .then(data => {
            songs = data.split('\n').filter(url => url.trim() !== '');
            initializePlayer();
            console.log(songs);
        })
        .catch(error => console.error('Error fetching songs:', error));

        function loadSong(index) {
            if (index >= 0 && index < songs.length) {
                audioPlayer.src = songs[index];
                setTimeout(() => {
                audioPlayer.play();
            }, 7000); 
          }
        }

        playButton.addEventListener('click', function() {
            if (audioPlayer.paused) {
                audioPlayer.play();
            } else {
                audioPlayer.pause();
            }
        });

        nextButton.addEventListener('click', function() {
            currentSongIndex = (currentSongIndex + 1) % songs.length;
            loadSong(currentSongIndex);
        });

        prevButton.addEventListener('click', function() {
            currentSongIndex = (currentSongIndex - 1 + songs.length) % songs.length;
            loadSong(currentSongIndex);
        });

       orderLoopButton.addEventListener('click', function() {
            if (isOrdered) {
                isOrdered = false;
                isLooping = !isLooping; 
                orderLoopButton.textContent = isLooping ? '循' : ''; 
            } else {
                isOrdered = true;
                isLooping = false; 
                orderLoopButton.textContent = '顺';
                
                loadSong(currentSongIndex); 
            }
        });  
        audioPlayer.addEventListener('ended', function() {
            if (isLooping) {
                audioPlayer.currentTime = 0; 
                audioPlayer.play(); 
            } else {
                currentSongIndex = (currentSongIndex + 1) % songs.length;
                loadSong(currentSongIndex);
            }
        });
        function initializePlayer() {
            if (songs.length > 0) {
            setTimeout(() => {
            loadSong(currentSongIndex);
        }, 7000); 
    }
}

</script>
</body>
</html>


<?php
date_default_timezone_set('Asia/Shanghai');

$currentTime = date(' H点i分s秒');
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>北京时间播报</title>
</head>
<body>
    <script>
        function speakCurrentTime() {
            var message = '当前北京时间是: <?php echo $currentTime; ?>';
            var utterance = new SpeechSynthesisUtterance(message);
            speechSynthesis.speak(utterance);
        }

        function checkForFullHour() {
            var now = new Date();
            if (now.getMinutes() === 0 && now.getSeconds() === 0) {
                speakCurrentTime();
            }
        }

        window.onload = function() {
            speakCurrentTime();
            setInterval(checkForFullHour, 1000);
        };
    </script>
</body>
</html>
