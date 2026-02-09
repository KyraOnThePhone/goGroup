-- Datenbank erstellen
CREATE DATABASE [GoGroup];

-- Tabellen Erstellen
-- Rolle
CREATE TABLE [ROLE](  
    [RoleId] int IDENTITY(1,1) primary key,
    [Name] varchar(100) NOT NULL
);

-- Benutzer
CREATE TABLE [USER](  
    [UserId] int IDENTITY(1,1) primary key,
    [FirstName] varchar(100) NOT NULL,
    [LastName] varchar(100) NOT NULL,
    [RoleId] int FOREIGN KEY REFERENCES [ROLE](RoleId) NOT NULL
);

-- Login
CREATE TABLE [LOGIN](  
    [LoginId] int IDENTITY(1,1) primary key,
    [Username] varchar(100) NOT NULL,
    [UserPassword] varchar(100) NOT NULL,
    [UserId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL
);

-- Kalender
CREATE TABLE [CALENDAR](  
    [CalendarId] int IDENTITY(1,1) primary key
);

-- Kalender Eintrag
CREATE TABLE [CALENDAR_ENTRY](  
    [CalendarEntryId] int IDENTITY(1,1) primary key,
    [CalendarId] int FOREIGN KEY REFERENCES [CALENDAR](CalendarId) NOT NULL,
    [Title] varchar(1000) NOT NULL,
    [Description] varchar(MAX),
    [StartDate] DATETIME NOT NULL,
    [EndDate] DATETIME NOT NULL,
);

--Gruppe
CREATE TABLE [GROUP](
    [GroupId] int IDENTITY(1,1) primary key,
    [Name] varchar(100) NOT NULL,
    [CalendarId] int FOREIGN KEY REFERENCES [CALENDAR](CalendarId) NOT NULL
);

--Projekt
CREATE TABLE [PROJECT](
    [ProjectId] int IDENTITY(1,1) primary key,
    [Title] varchar(1000) NOT NULL,
    [DueDate] DATETIME,
    [Description] varchar(MAX),
    [GroupId] int FOREIGN KEY REFERENCES [GROUP](GroupId) NOT NULL
);

-- Tabellen-Nr
CREATE TABLE [TABLENUMBER](
    [TableNumber] int IDENTITY(1,1) primary key,
    [Name] varchar(100) NOT NULL
);

--Status
CREATE TABLE [STATUS](
    [StatusId] int IDENTITY(1,1) primary key,
    [Name] varchar(100) NOT NULL,
    [RegardingTableNumber] int FOREIGN KEY REFERENCES [TABLENUMBER](TableNumber) NOT NULL
);

--Aufgabe
CREATE TABLE [TASK](
    [TaskId] int IDENTITY(1,1) primary key,
    [Title] varchar(1000) NOT NULL,
    [Description] varchar(MAX),
    [StateId] int FOREIGN KEY REFERENCES [STATUS](StatusId) NOT NULL,
    [DueDate] DATETIME,
    [OwnerId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL,
    [ProjectId] int FOREIGN KEY REFERENCES [PROJECT](ProjectId) NOT NULL
);

-- Chat
CREATE TABLE [CHAT](
    [ChatId] int IDENTITY(1,1) primary key,
    [Name] VARCHAR(1000) NOT NULL,    
    [GroupId] int FOREIGN KEY REFERENCES [GROUP](GroupId),
);

-- Mitglied
CREATE TABLE [MEMBER](
    [MemberId] int IDENTITY(1,1) primary key,
    [GroupId] int FOREIGN KEY REFERENCES [GROUP](GroupId) NOT NULL,
    [UserId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL,
    [ChatId] int FOREIGN KEY REFERENCES [CHAT](ChatId)
);