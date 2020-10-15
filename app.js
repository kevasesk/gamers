    window.type = 'games';
    var Gamers = {
        dataField: document.getElementById('dataField'),
        debug: document.getElementById('debug'),
        result: document.getElementById('result'),
        members: [],
        totalGamers: null,
        resultsVariant: null,

        data: {},

        results: {
            2: '[[{"first":0,"second":1}]]',
            3: '[[{"first":0,"second":1}],[{"first":0,"second":2}],[{"first":1,"second":2}]]',
            4: '[[{"first":0,"second":1},{"first":2,"second":3}],[{"first":0,"second":2},{"first":1,"second":3}],[{"first":0,"second":3},{"first":1,"second":2}]]',
            5: '[[{"first":0,"second":1},{"first":2,"second":4}],[{"first":0,"second":2},{"first":3,"second":4}],[{"first":0,"second":3},{"first":1,"second":2}],[{"first":0,"second":4},{"first":1,"second":3}],[{"first":2,"second":3},{"first":1,"second":4}]]',
            6: '[[{"first":0,"second":1},{"first":3,"second":5},{"first":2,"second":4}],[{"first":0,"second":2},{"first":1,"second":3},{"first":4,"second":5}],[{"first":0,"second":3},{"first":2,"second":5},{"first":1,"second":4}],[{"first":0,"second":4},{"first":2,"second":3},{"first":1,"second":5}],[{"first":0,"second":5},{"first":1,"second":2},{"first":3,"second":4}]]',
            7: '[[{"first":0,"second":1},{"first":2,"second":6},{"first":4,"second":5}],[{"first":0,"second":2},{"first":1,"second":3},{"first":4,"second":6}],[{"first":0,"second":3},{"first":5,"second":6},{"first":2,"second":4}],[{"first":0,"second":4},{"first":3,"second":5},{"first":1,"second":2}],[{"first":0,"second":5},{"first":2,"second":3},{"first":1,"second":6}],[{"first":0,"second":6},{"first":1,"second":5},{"first":3,"second":4}],[{"first":3,"second":6},{"first":1,"second":4},{"first":2,"second":5}]]',
            8: '[[{"first":0,"second":1},{"first":3,"second":7},{"first":5,"second":6},{"first":2,"second":4}],[{"first":0,"second":2},{"first":6,"second":7},{"first":3,"second":5},{"first":1,"second":4}],[{"first":0,"second":3},{"first":4,"second":7},{"first":2,"second":6},{"first":1,"second":5}],[{"first":0,"second":4},{"first":3,"second":6},{"first":1,"second":7},{"first":2,"second":5}],[{"first":0,"second":5},{"first":2,"second":7},{"first":4,"second":6},{"first":1,"second":3}],[{"first":0,"second":6},{"first":5,"second":7},{"first":1,"second":2},{"first":3,"second":4}],[{"first":0,"second":7},{"first":1,"second":6},{"first":2,"second":3},{"first":4,"second":5}]]',
            9: '[[{"first":0,"second":1},{"first":3,"second":8},{"first":6,"second":7},{"first":4,"second":5}],[{"first":0,"second":2},{"first":4,"second":6},{"first":1,"second":3},{"first":5,"second":8}],[{"first":0,"second":3},{"first":1,"second":2},{"first":4,"second":7},{"first":6,"second":8}],[{"first":0,"second":4},{"first":2,"second":8},{"first":1,"second":7},{"first":3,"second":5}],[{"first":0,"second":5},{"first":1,"second":4},{"first":3,"second":7},{"first":2,"second":6}],[{"first":0,"second":6},{"first":2,"second":5},{"first":7,"second":8},{"first":3,"second":4}],[{"first":0,"second":7},{"first":1,"second":8},{"first":2,"second":3},{"first":5,"second":6}],[{"first":0,"second":8},{"first":1,"second":6},{"first":5,"second":7},{"first":2,"second":4}],[{"first":1,"second":5},{"first":2,"second":7},{"first":4,"second":8},{"first":3,"second":6}]]',
            10: '[[{"first":0,"second":1},{"first":4,"second":9},{"first":6,"second":8},{"first":5,"second":7},{"first":2,"second":3}],[{"first":0,"second":2},{"first":4,"second":5},{"first":3,"second":7},{"first":8,"second":9},{"first":1,"second":6}],[{"first":0,"second":3},{"first":4,"second":8},{"first":2,"second":7},{"first":5,"second":6},{"first":1,"second":9}],[{"first":0,"second":4},{"first":3,"second":8},{"first":5,"second":9},{"first":1,"second":7},{"first":2,"second":6}],[{"first":0,"second":5},{"first":4,"second":7},{"first":3,"second":6},{"first":1,"second":8},{"first":2,"second":9}],[{"first":0,"second":6},{"first":2,"second":8},{"first":3,"second":5},{"first":1,"second":4},{"first":7,"second":9}],[{"first":0,"second":7},{"first":3,"second":4},{"first":5,"second":8},{"first":6,"second":9},{"first":1,"second":2}],[{"first":0,"second":8},{"first":1,"second":5},{"first":2,"second":4},{"first":6,"second":7},{"first":3,"second":9}],[{"first":0,"second":9},{"first":4,"second":6},{"first":2,"second":5},{"first":7,"second":8},{"first":1,"second":3}]]',
            11: '[[{"first":0,"second":1},{"first":7,"second":8},{"first":3,"second":5},{"first":2,"second":9},{"first":4,"second":10}],[{"first":0,"second":2},{"first":3,"second":7},{"first":1,"second":8},{"first":4,"second":9},{"first":6,"second":10}],[{"first":0,"second":3},{"first":5,"second":6},{"first":2,"second":8},{"first":7,"second":9},{"first":1,"second":4}],[{"first":0,"second":4},{"first":1,"second":2},{"first":9,"second":10},{"first":5,"second":7},{"first":3,"second":6}],[{"first":0,"second":5},{"first":1,"second":6},{"first":2,"second":10},{"first":3,"second":8},{"first":4,"second":7}],[{"first":0,"second":6},{"first":2,"second":5},{"first":1,"second":10},{"first":8,"second":9},{"first":3,"second":4}],[{"first":0,"second":7},{"first":4,"second":5},{"first":3,"second":9},{"first":8,"second":10},{"first":2,"second":6}],[{"first":0,"second":8},{"first":1,"second":9},{"first":5,"second":10},{"first":6,"second":7},{"first":2,"second":3}],[{"first":0,"second":9},{"first":5,"second":8},{"first":4,"second":6},{"first":1,"second":3},{"first":7,"second":10}],[{"first":0,"second":10},{"first":6,"second":9},{"first":4,"second":8},{"first":1,"second":5},{"first":2,"second":7}],[{"first":1,"second":7},{"first":6,"second":8},{"first":2,"second":4},{"first":5,"second":9},{"first":3,"second":10}]]',
            12: '[[{"first":0,"second":1},{"first":8,"second":9},{"first":6,"second":10},{"first":3,"second":5},{"first":4,"second":11},{"first":2,"second":7}],[{"first":0,"second":2},{"first":8,"second":11},{"first":1,"second":7},{"first":5,"second":10},{"first":3,"second":6},{"first":4,"second":9}],[{"first":0,"second":3},{"first":7,"second":9},{"first":2,"second":6},{"first":1,"second":11},{"first":4,"second":10},{"first":5,"second":8}],[{"first":0,"second":4},{"first":3,"second":8},{"first":6,"second":9},{"first":5,"second":7},{"first":1,"second":2},{"first":10,"second":11}],[{"first":0,"second":5},{"first":2,"second":3},{"first":7,"second":10},{"first":1,"second":4},{"first":9,"second":11},{"first":6,"second":8}],[{"first":0,"second":6},{"first":2,"second":11},{"first":4,"second":5},{"first":7,"second":8},{"first":1,"second":9},{"first":3,"second":10}],[{"first":0,"second":7},{"first":2,"second":9},{"first":4,"second":8},{"first":5,"second":6},{"first":1,"second":10},{"first":3,"second":11}],[{"first":0,"second":8},{"first":1,"second":5},{"first":3,"second":7},{"first":9,"second":10},{"first":6,"second":11},{"first":2,"second":4}],[{"first":0,"second":9},{"first":5,"second":11},{"first":2,"second":10},{"first":3,"second":4},{"first":6,"second":7},{"first":1,"second":8}],[{"first":0,"second":10},{"first":2,"second":8},{"first":1,"second":3},{"first":4,"second":6},{"first":7,"second":11},{"first":5,"second":9}],[{"first":0,"second":11},{"first":1,"second":6},{"first":8,"second":10},{"first":4,"second":7},{"first":2,"second":5},{"first":3,"second":9}]]',
            13: '[[{"first":0,"second":1},{"first":7,"second":11},{"first":3,"second":4},{"first":6,"second":9},{"first":2,"second":8},{"first":5,"second":10}],[{"first":0,"second":2},{"first":1,"second":11},{"first":5,"second":9},{"first":4,"second":10},{"first":6,"second":12},{"first":7,"second":8}],[{"first":0,"second":3},{"first":6,"second":11},{"first":9,"second":10},{"first":2,"second":4},{"first":5,"second":12},{"first":1,"second":8}],[{"first":0,"second":4},{"first":3,"second":9},{"first":8,"second":11},{"first":2,"second":10},{"first":1,"second":12},{"first":6,"second":7}],[{"first":0,"second":5},{"first":9,"second":12},{"first":2,"second":7},{"first":8,"second":10},{"first":1,"second":4},{"first":3,"second":6}],[{"first":0,"second":6},{"first":1,"second":2},{"first":3,"second":8},{"first":10,"second":11},{"first":7,"second":12},{"first":4,"second":5}],[{"first":0,"second":7},{"first":5,"second":6},{"first":3,"second":11},{"first":8,"second":9},{"first":1,"second":10},{"first":4,"second":12}],[{"first":0,"second":8},{"first":9,"second":11},{"first":6,"second":10},{"first":3,"second":5},{"first":4,"second":7},{"first":2,"second":12}],[{"first":0,"second":9},{"first":1,"second":3},{"first":5,"second":7},{"first":4,"second":11},{"first":8,"second":12},{"first":2,"second":6}],[{"first":0,"second":10},{"first":4,"second":8},{"first":3,"second":7},{"first":1,"second":9},{"first":2,"second":5},{"first":11,"second":12}],[{"first":0,"second":11},{"first":4,"second":6},{"first":3,"second":12},{"first":1,"second":5},{"first":2,"second":9},{"first":7,"second":10}],[{"first":0,"second":12},{"first":3,"second":10},{"first":2,"second":11},{"first":7,"second":9},{"first":5,"second":8},{"first":1,"second":6}],[{"first":2,"second":3},{"first":4,"second":9},{"first":5,"second":11},{"first":1,"second":7},{"first":6,"second":8},{"first":10,"second":12}]]',
            14: '[[{"first":0,"second":1},{"first":4,"second":11},{"first":8,"second":10},{"first":7,"second":12},{"first":2,"second":5},{"first":3,"second":9},{"first":6,"second":13}],[{"first":0,"second":2},{"first":6,"second":9},{"first":1,"second":5},{"first":10,"second":11},{"first":3,"second":4},{"first":7,"second":13},{"first":8,"second":12}],[{"first":0,"second":3},{"first":1,"second":6},{"first":5,"second":9},{"first":4,"second":10},{"first":2,"second":7},{"first":8,"second":13},{"first":11,"second":12}],[{"first":0,"second":4},{"first":1,"second":13},{"first":2,"second":8},{"first":6,"second":11},{"first":3,"second":5},{"first":10,"second":12},{"first":7,"second":9}],[{"first":0,"second":5},{"first":4,"second":7},{"first":1,"second":8},{"first":9,"second":12},{"first":3,"second":6},{"first":2,"second":10},{"first":11,"second":13}],[{"first":0,"second":6},{"first":1,"second":10},{"first":2,"second":3},{"first":7,"second":11},{"first":9,"second":13},{"first":5,"second":12},{"first":4,"second":8}],[{"first":0,"second":7},{"first":5,"second":11},{"first":2,"second":4},{"first":6,"second":8},{"first":12,"second":13},{"first":9,"second":10},{"first":1,"second":3}],[{"first":0,"second":8},{"first":4,"second":13},{"first":1,"second":12},{"first":5,"second":7},{"first":2,"second":6},{"first":3,"second":10},{"first":9,"second":11}],[{"first":0,"second":9},{"first":1,"second":11},{"first":5,"second":10},{"first":3,"second":12},{"first":7,"second":8},{"first":4,"second":6},{"first":2,"second":13}],[{"first":0,"second":10},{"first":4,"second":12},{"first":1,"second":9},{"first":3,"second":8},{"first":2,"second":11},{"first":6,"second":7},{"first":5,"second":13}],[{"first":0,"second":11},{"first":7,"second":10},{"first":4,"second":5},{"first":8,"second":9},{"first":3,"second":13},{"first":6,"second":12},{"first":1,"second":2}],[{"first":0,"second":12},{"first":5,"second":6},{"first":1,"second":4},{"first":8,"second":11},{"first":10,"second":13},{"first":2,"second":9},{"first":3,"second":7}],[{"first":0,"second":13},{"first":1,"second":7},{"first":6,"second":10},{"first":3,"second":11},{"first":4,"second":9},{"first":2,"second":12},{"first":5,"second":8}]]',
            15: '[[{"first":0,"second":1},{"first":2,"second":7},{"first":6,"second":10},{"first":3,"second":12},{"first":8,"second":14},{"first":4,"second":13},{"first":5,"second":9}],[{"first":0,"second":2},{"first":3,"second":8},{"first":4,"second":12},{"first":1,"second":11},{"first":9,"second":14},{"first":6,"second":13},{"first":7,"second":10}],[{"first":0,"second":3},{"first":2,"second":10},{"first":1,"second":12},{"first":4,"second":5},{"first":6,"second":8},{"first":11,"second":13},{"first":7,"second":9}],[{"first":0,"second":4},{"first":8,"second":13},{"first":5,"second":10},{"first":6,"second":12},{"first":9,"second":11},{"first":7,"second":14},{"first":2,"second":3}],[{"first":0,"second":5},{"first":9,"second":12},{"first":3,"second":10},{"first":1,"second":7},{"first":4,"second":6},{"first":8,"second":11},{"first":2,"second":14}],[{"first":0,"second":6},{"first":1,"second":8},{"first":3,"second":4},{"first":11,"second":14},{"first":12,"second":13},{"first":5,"second":7},{"first":9,"second":10}],[{"first":0,"second":7},{"first":1,"second":5},{"first":2,"second":8},{"first":10,"second":13},{"first":3,"second":11},{"first":4,"second":14},{"first":6,"second":9}],[{"first":0,"second":8},{"first":12,"second":14},{"first":10,"second":11},{"first":4,"second":7},{"first":1,"second":2},{"first":3,"second":13},{"first":5,"second":6}],[{"first":0,"second":9},{"first":1,"second":4},{"first":3,"second":14},{"first":2,"second":11},{"first":8,"second":12},{"first":6,"second":7},{"first":5,"second":13}],[{"first":0,"second":10},{"first":2,"second":13},{"first":3,"second":5},{"first":7,"second":12},{"first":1,"second":14},{"first":4,"second":9},{"first":6,"second":11}],[{"first":0,"second":11},{"first":7,"second":8},{"first":5,"second":14},{"first":1,"second":6},{"first":2,"second":4},{"first":10,"second":12},{"first":9,"second":13}],[{"first":0,"second":12},{"first":4,"second":11},{"first":13,"second":14},{"first":3,"second":7},{"first":1,"second":10},{"first":2,"second":5},{"first":8,"second":9}],[{"first":0,"second":13},{"first":8,"second":10},{"first":6,"second":14},{"first":5,"second":12},{"first":2,"second":9},{"first":1,"second":3},{"first":7,"second":11}],[{"first":0,"second":14},{"first":4,"second":10},{"first":5,"second":8},{"first":1,"second":13},{"first":11,"second":12},{"first":3,"second":9},{"first":2,"second":6}],[{"first":1,"second":9},{"first":7,"second":13},{"first":4,"second":8},{"first":3,"second":6},{"first":2,"second":12},{"first":5,"second":11},{"first":10,"second":14}]]'
        },

        init: function () {
            Gamers.result.innerHTML = '';
            this.data.type = 'games';//set data
            this.drawSchedule();
        },
        load: function(){
//            var data = Android.load(); TODO REMOVE
//            this.data = JSON.parse(data);
//            this.debug.innerHTML = data;
            this.fillData();
        },
        save: function(){
            this.collectData();
            //Android.save(JSON.stringify(this.data)); TODO REMOVE
        },
        collectData: function (){
            for (var tour = 0; tour < this.resultsVariant.length; tour++) {
                for (var game = 0; game < this.resultsVariant[0].length; game++) {

                }
            }

        },
        fillData: function(){
            this.data = {
                members: ['11','22','33','44'],
                locked:[],
                scores:{
                    0:{
                        1:2,
                        2:15,
                        3:22
                    },
                    4:{//couple
                        2: 15 //game : score
                    }
                },
                games:{
                    0:{//couple
                        1:{//gamer
                            0:true,//game: win
                            1:true,//game: win
                            2:false,//game: lost
                        },
                        2:{
                            0:false,//game: win
                            1:false,//game: win
                            2:true,//game: lost
                        }

                    }
                }
            };

            //fill gamers
            Gamers.dataField.innerHTML = this.data.members.join('\n');
            //render schedule for getting elements to fill
            this.drawSchedule();

            //fill locks
            for (var lockIndex = 0; lockIndex < this.data.locked.length; lockIndex++) {
                $('[data-couple-lock="'+lockIndex+'"]').trigger('click');
            }

            //fill scores for games type
            for (var couple in this.data.scores) {
                if(this.data.scores[couple] !== undefined){
                     for (var game in this.data.scores[couple]) {
                           if(this.data.scores[couple][game] !== undefined){
                                $('[name="couple_'+couple+'_'+game+'"] option[value='+this.data.scores[couple][game]+']').prop('selected', true);
                           }
                     }
                }
            }

            //fill gamers
            //data-couple="'+couple+'" data-tour="'+tour+'" data-gamer="'+gamer+'"

        },
        drawSchedule: function () {
            this.members = this.dataField.value.split("\n");
            this.data.members = this.members;//set data
            this.totalGamers = this.members.length;
            if(this.members.length in this.results){
                this.resultsVariant = JSON.parse(this.results[this.members.length]);

                //TODO validate for similar
                //TODO add similar check for score
                var html = '';
                var couple = 0;
                for (var tour = 0; tour < this.resultsVariant.length; tour++) {
                    html += '<table class="table table-hover">';
                    var colspan = 5;
                    if(window.type == 'scores'){
                        colspan = 4;
                    }
                    html += '<thead class="thead-dark"><tr><th colspan="' + colspan + '">Тур ' + (tour + 1) + '</th></tr></thead>';
                    for (var game = 0; game < this.resultsVariant[0].length; game++) {
                        if(window.type == 'games'){
                            html +='<tr><td></td>' +
                                Gamers.getScoreTemplate(couple, 1, null) +
                                Gamers.getScoreTemplate(couple, 2, null) +
                                Gamers.getScoreTemplate(couple, 3, null) +
                                Gamers.getLockTemplate(couple) +
                            '</tr>';
                            html += '<tr><td class="with-border couple_'+couple+'">' + this.members[this.resultsVariant[tour][game]['first']] + '</td>' +
                                Gamers.getGameTemplate(couple, this.resultsVariant[tour][game]['first'], 1, tour) +
                                Gamers.getGameTemplate(couple, this.resultsVariant[tour][game]['first'], 2, tour) +
                                Gamers.getGameTemplate(couple, this.resultsVariant[tour][game]['first'], 3, tour) +
                                //'<td></td>'
                            '</tr>';
                            html += '<tr><td class="with-border couple_'+couple+'">' + this.members[this.resultsVariant[tour][game]['second']] + '</td>' +
                                Gamers.getGameTemplate(couple, this.resultsVariant[tour][game]['second'], 1, tour) +
                                Gamers.getGameTemplate(couple, this.resultsVariant[tour][game]['second'], 2, tour) +
                                Gamers.getGameTemplate(couple, this. resultsVariant[tour][game]['second'], 3, tour) +
                                //'<td></td>'
                            '</tr>';
                        }
                        if(window.type == 'scores'){
                            html += '<tr><td class="with-border couple_'+couple+'">' + this.members[this.resultsVariant[tour][game]['first']] + '</td>' +
                                Gamers.getGameTemplate(couple, this.resultsVariant[tour][game]['first'], 1, tour) +
                                Gamers.getScoreTemplate(couple, 1, this.resultsVariant[tour][game]['first']) +
                                Gamers.getLockTemplate(couple) +
                                //'<td></td>'
                            '</tr>';
                            html += '<tr><td class="with-border couple_'+couple+'">' + this.members[this.resultsVariant[tour][game]['second']] + '</td>' +
                                Gamers.getGameTemplate(couple, this.resultsVariant[tour][game]['second'], 1, tour) +
                                Gamers.getScoreTemplate(couple, 1, this.resultsVariant[tour][game]['second']) +
                                //'<td></td>'
                            '</tr>';
                        }

                        if(game < this.resultsVariant.length - 3){
                            html += '<tr style="background-color:#00a259;"><td colspan="' + colspan + '"></td></tr>';
                        }
                        couple++;
                    }

                    html += '</table><br/><br/>';
                }
                Gamers.result.innerHTML = html;
            }else{
                Gamers.result.innerHTML = 'Ну Женщина, незя на столько людей :(';
            }
        },
        drawWinners: function(){
            var winersElement = document.getElementById('winers');
            winersElement.innerHTML = '';
            var winners = [];
            for(var gamerIndex = 0; gamerIndex < Gamers.totalGamers; gamerIndex++){
                var totalGamerScore = 0;
                for (var tour = 0; tour < this.resultsVariant.length; tour++) {
                    var tourGamerScore = 0;
                    if(window.type == 'games'){
                        var gamerRadios = document.querySelectorAll('[data-tour="'+tour+'"][data-gamer="'+gamerIndex+'"]');
                        gamerRadios.forEach((element) => {
                            if(element.checked){
                                tourGamerScore++;
                            }
                        });
                        if(tourGamerScore >= 2){
                            totalGamerScore++;
                        }
                    }
                }

                if(window.type == 'scores'){
                    var totalGamerScore = 0;
                    var gamerScores = document.querySelectorAll('select[data-gamer="'+gamerIndex+'"]');
                    gamerScores.forEach((element) => {
                        totalGamerScore += parseInt(element.value);
                    });
                }

                winners.push({
                    gamer: gamerIndex,
                    score: totalGamerScore
                });
            }
            winners.sort((a, b) => (a.score < b.score) ? 1 : -1);

            var html ='<table class="table table-hover">';
            var place = 1;

            html += '<thead class="thead-dark"><tr>';
                html += '<th class="with-border">Место</th>';
                html += '<th class="with-border">Имя</th>';
                if(window.type == 'games'){
                    html += '<th class="with-border">Победы</th>';
                }
                if(window.type == 'scores'){
                    html += '<th class="with-border">Очки</th>';
                }
            html += '</tr></thead>';

            winners.forEach((gamerObject) => {
                html += '<tr>';
                    html += '<td class="with-border">'+place+'</td>';
                    html += '<td class="with-border">'+this.members[gamerObject.gamer]+'</td>';
                    html += '<td class="with-border">'+gamerObject.score+'</td>';
                html += '</tr>';
                place++;
            });
            html += '</table>';
            winersElement.innerHTML = html;
        },
        getGameTemplate: function(couple, gamer, game, tour){
            return '<td class="with-border">'+
                '<input type="radio" name="couple_'+couple+'_'+game+'" data-couple="'+couple+'" data-tour="'+tour+'" data-gamer="'+gamer+'" onclick="Gamers.drawWinners();"/>'+
            '</td>';
        },
        getScoreTemplate: function(couple, game, gamer){
            var select = '<select name="couple_'+couple+'_'+game+'" data-couple="'+couple+'" data-game="'+game+'" data-gamer="'+gamer+'" data-score>';
            var from = 0;
            var to = 30;
            if(window.type == 'scores'){
                from = -30;
                to = 30;
            }
            for(var i = from; i<=to; i++){
                if(i == 0 ){
                    select += '<option selected>'+i+'</option>';
                }else {
                    select += '<option value="'+i+'">'+i+'</option>';
                }
            }
            select += '</select>';
            return '<td class="with-border">'+select+'</td>';
        },
        getLockTemplate: function(couple){
            var rowspan = 3;
            if(window.type == 'scores'){
                rowspan = 2;
            }
            return '<td rowspan="' + rowspan + '">'+
                '<input type="checkbox" class="lock" data-couple-lock='+couple+' onchange="Gamers.handleLock(this,'+couple+')"/>'+
            '</td>';
        },
        handleLock: function(elem, couple, game){
            var fieldsToLock = document.querySelectorAll('[data-couple="'+couple+'"]');
            if(elem.checked){
                fieldsToLock.forEach((element) => {
                    element.disabled = true;
                });
            } else {
                fieldsToLock.forEach((element) => {
                    element.disabled = false;
                });
            }
        },
        handleTypeChange: function(element){
            window.type = element.value;
            this.data.type = window.type;//set data
            this.drawSchedule();
            this.drawWinners();
        }
    };

    document.addEventListener("change", function(e){
        if(e.target.dataset.score !== undefined && window.type == 'scores'){
            Gamers.drawWinners();
        }
    });
