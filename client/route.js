import { Meteor } from 'meteor/meteor';

Meteor.startup(function () {
    User = new Meteor.Collection('user');
    Accounts.createUser({
		email: "harshad.pawar87@gmail.com",
		password: "123456"
	});
});

Router.configure({
  // the default layout
  layoutTemplate: 'mainNav'
});
 
Router.route('/', function () {
	this.render('loginPage');
	this.layout('mainNav');
});

Router.route('/listUsers', function () {
  this.render('listUsers');
  this.layout('mainNav');
}); 
 
Router.route('/addUser', function () {
  this.render('addUser');
  this.layout('mainNav');
});

Router.route('/editUser',{
    path: '/editUser/:uid',
    template: 'addUser',
    layoutTemplate: 'mainNav',
    data: function(argument) {
    	return User.findOne(this.params.uid);
    }/*,
    action: function() {
    	var obj = this;
    	var url = 'http://localhost:3000/users/' + this.params.uid;
		HTTP.get( url, {}, function( error, response ) {
			var resultJson = JSON.parse(response.content);
			debugger;
			$('input[name="uid"]').val(resultJson[0]._id);
			$('input[name="userName"]').val(resultJson[0].userName);
			$('input[name="firstName"]').val(resultJson[0].firstName);
			$('input[name="lastName"]').val(resultJson[0].lastName);
			$('input[name="emailID"]').val(resultJson[0].emailID);
			obj.render('addUser');
		});
    }*/
});

if(Meteor.isClient) {
	Template.loginUser.events({
		'submit form.login-user': function(event) {
		    event.preventDefault();
		    var emailVar = event.target.loginEmail.value;
		    var passwordVar = event.target.loginPassword.value;
		    Meteor.loginWithPassword(emailVar, passwordVar);
		}
	});

	Template.dashboard.events({
	    'click .logout': function(event){
	        event.preventDefault();
	        Meteor.logout();
	        Router.go("/");
	    }
	});

	Template.dashboard.helpers({
        'userName': function(){
        	var userDetail = Meteor.user();
        	if(userDetail.emails[0].address) {
        		return userDetail.emails[0].address;
        	} else {
        		return "";
        	}
        }
    });

    Template.listUsers.helpers({
    	'userList': function() {
    		return User.find({}, {sort: {firstName: -1}});
    	}
    })

    Template.addUser.events({
    	'submit form': function(event, template) {
    		event.preventDefault();
    		var uid = event.target.uid.value;
    		if(uid) {
    			var userName = event.target.userName.value;
				var firstName = event.target.firstName.value;
				var lastName = event.target.lastName.value;
				var emailID = event.target.emailID.value;
				var updateData = {
					data: {
						"userName": userName,
						"firstName": firstName,
						"lastName": lastName,
						"emailID": emailID,
					}
				}
				var url = 'http://localhost:3000/users/' + uid;
				HTTP.put( url, updateData, function( error, response ) {
					var resultJson = JSON.parse(response.content);
					if (response.statusCode == 200 && !resultJson.error) {
						Router.go("/listUsers");
					} else {
						Router.go("/editUser/" + uid);
					}
				});
    		} else {
    			var userName = event.target.userName.value;
				var firstName = event.target.firstName.value;
				var lastName = event.target.lastName.value;
				var emailID = event.target.emailID.value;
				HTTP.call('POST', 'http://localhost:3000/users', {
						data: { userName: userName, firstName: firstName, lastName: lastName, emailID: emailID}
				}, (error, result) => {
					var resultJson = JSON.parse(result.content);
					if (result.statusCode == 200 && !resultJson.error) {
						//template.find(".alert-success").show();
					} else {
						//template.find(".alert-error").show();
					}
				});
    		}
    	}
    });

    Template.listUsers.events({
    	'click .delete-user': function(event) {
    		event.preventDefault();
            var documentId = this._id;
            var confirm = window.confirm("Delete this task?");
            if(confirm) {
            	var deleteData = {
					data: {}
				}
				var url = 'http://localhost:3000/users/'+documentId;
				HTTP.del( url, deleteData, 
					function( error, response ) {
						var resultJson = JSON.parse(response.content);
					}
				);
            }
        },
        'click .edit-user': function(event) {
        	event.preventDefault();
        	var userID = this._id;
        	Router.go("/editUser/"+userID);
        }
    });
}