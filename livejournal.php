<?php

class lifejournal
{

public interface ILj:IXmlRpcProxy
{
	[XmlRpcMethod("LJ.XMLRPC.login")]
	LjUserInfo Login(UserPassword user); 

	XmlRpcMethod("LJ.XMLRPC.postevent")] 
	PostLjAnswer Post(PostLj post); 
}

public class UserPassword
{   
	[JsonProperty("username")]
	public string username { get; set; } 

	[JsonProperty("password")]
	public string password { get; set; }   

	public int ver   {
		get     {
			return 1; 
		} 
	} 
}

public LjUserInfo Auth(UserPassword username) 
{ 
	ILj proxy = XmlRpcProxyGen.Create<ILj>(); 
	var ans = proxy.Login(username);
	return ans; 
} 

public void Publish(UserPassword username, Post message, string ljgroup = null) 
{ 
	ILj proxy = XmlRpcProxyGen.Create<ILj>();  
	var post = new PostLj();   
	post.username = username.username;   
	post.password = username.password;   
	post.ver = 1;   
	post.@event = message.Content;   
	post.subject = message.Title;   
	post.lineendings = "pc";   
	post.year = DateTime.Now.Year;   
	post.mon = DateTime.Now.Month;   
	post.day = DateTime.Now.Day;   
	post.hour = DateTime.Now.Hour;   
	post.min = DateTime.Now.Minute;   
	if (!string.IsNullOrWhiteSpace(ljgroup))   {  
		post.usejournal = ljgroup;   
	}   else   {     
		post.usejournal = username.username;   
	}   
	var ans = proxy.Post(post); 
}

}